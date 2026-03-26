<?php

namespace App\Console\Commands;

use App\Models\Bouteille;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class ImporterSaq extends Command
{
  protected $signature = 'saq:import';
  protected $description = 'Importer une sélection de 50 vins depuis la SAQ';

  public function handle()
  {
    try {
      $page = 1;
      $pageSize = 50;
      $totalImporte = 0;
      $totalIgnores = 0;

      $this->info("Import de {$pageSize} produits maximum...");

      $query = <<<GRAPHQL
            {
              productSearch(phrase: "", page_size: $pageSize, current_page: $page) {
                items {
                  productView {
                    name
                    sku
                    attributes {
                      label
                      name
                      value
                    }
                  }
                  product {
                    sku
                    image {
                      url
                    }
                    price_range {
                      minimum_price {
                        regular_price {
                          value
                        }
                      }
                    }
                  }
                }
              }
            }
            GRAPHQL;

      $response = Http::withOptions([
        'verify' => false,
        'curl' => [
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_SSL_VERIFYHOST => 0,
        ],
      ])->withHeaders([
        'x-api-key' => env('SAQ_API_KEY'),
        'magento-customer-group' => env('SAQ_CUSTOMER_GROUP'),
        'magento-environment-id' => env('SAQ_ENVIRONMENT_ID'),
        'magento-store-code' => env('SAQ_STORE_CODE'),
        'magento-store-view-code' => env('SAQ_STORE_VIEW_CODE'),
        'magento-website-code' => env('SAQ_WEBSITE_CODE'),
        'Content-Type' => 'application/json',
      ])->post(env('SAQ_GRAPHQL_URL'), [
        'query' => $query,
      ]);

      if ($response->failed()) {
        $this->error('Erreur lors de la requête à la SAQ.');
        $this->line($response->body());
        return self::FAILURE;
      }

      $data = $response->json();
      $items = $data['data']['productSearch']['items'] ?? [];

      $this->info('Nombre d’items reçus : ' . count($items));

      foreach ($items as $item) {
        $attributes = $item['productView']['attributes'] ?? [];

        $codeSaq = $item['productView']['sku'] ?? null;
        $nom = $item['productView']['name'] ?? null;

        $type = $this->trouverAttribut($attributes, 'identite_produit');
        $pays = $this->trouverAttribut($attributes, 'pays_origine');
        $cepage = $this->trouverAttribut($attributes, 'cepage');
        $millesime = $this->trouverAttribut($attributes, 'millesime_produit');
        $tauxAlcool = $this->trouverAttribut($attributes, 'pourcentage_alcool_par_volume');
        $format = $this->trouverAttribut($attributes, 'format_contenant_ml');
        $pastilleGout = $this->trouverAttribut($attributes, 'pastille_gout');
        $image = $item['product']['image']['url'] ?? null;

        if (empty($codeSaq) || empty($nom) || empty($type)) {
          $totalIgnores++;
          continue;
        }

        if (!$this->estUnVin($type)) {
          $totalIgnores++;
          continue;
        }

        Bouteille::updateOrCreate(
          [
            'code_saq' => $codeSaq,
          ],
          [
            'nom' => $nom,
            'type' => $type,
            'pays' => $pays,
            'cepage' => $cepage,
            'millesime' => is_numeric($millesime) ? (int) $millesime : null,
            'taux_alcool' => $this->nettoyerDecimal($tauxAlcool),
            'prix' => $item['product']['price_range']['minimum_price']['regular_price']['value'] ?? null,
            'format' => $this->nettoyerEntier($format),
            'image' => $image,
            'pastille_gout' => $pastilleGout,
            'est_saq' => true,
          ]
        );

        $totalImporte++;
      }

      $this->info("Import terminé avec succès.");
      $this->info("Nombre de vins importés : {$totalImporte}");
      $this->info("Nombre d’items ignorés : {$totalIgnores}");

      return self::SUCCESS;
    } catch (Throwable $e) {
      $this->error('Erreur : ' . $e->getMessage());
      $this->line('Fichier : ' . $e->getFile());
      $this->line('Ligne : ' . $e->getLine());

      return self::FAILURE;
    }
  }

  private function trouverAttribut(array $attributes, string $nomRecherche): ?string
  {
    foreach ($attributes as $attribute) {
      if (($attribute['name'] ?? '') === $nomRecherche) {
        $valeur = $attribute['value'] ?? null;

        if (is_array($valeur)) {
          return implode(', ', array_map('strval', $valeur));
        }

        return $valeur !== null ? (string) $valeur : null;
      }
    }

    return null;
  }

  private function nettoyerDecimal(?string $valeur): ?float
  {
    if ($valeur === null || $valeur === '') {
      return null;
    }

    $valeur = str_replace(['%', ','], ['', '.'], trim($valeur));

    return is_numeric($valeur) ? (float) $valeur : null;
  }

  private function nettoyerEntier(?string $valeur): ?int
  {
    if ($valeur === null || $valeur === '') {
      return null;
    }

    $valeur = preg_replace('/[^0-9]/', '', $valeur);

    return is_numeric($valeur) ? (int) $valeur : null;
  }

  private function estUnVin(string $type): bool
  {
    $type = mb_strtolower(trim($type));

    $typesAcceptes = [
      'vin rouge',
      'vin blanc',
      'vin rosé',
      'vin mousseux',
      'vin orange',
      'vin',
      'porto',
      'vin fortifié',
      'champagne',
    ];

    foreach ($typesAcceptes as $typeAccepte) {
      if (str_contains($type, $typeAccepte)) {
        return true;
      }
    }

    return false;
  }
}
