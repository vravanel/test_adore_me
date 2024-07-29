<?php

function productData()
{
    $data = file_get_contents('https://gw-services.prd.adoreme.com/v2/feeds/veesual');
    $products = json_decode($data, true);

    $productsData = [];

    foreach ($products as $product) {
        $productId = $product['id'];
        $transformedProduct = [];

        if (isset($product['tops'])) {
            $bandSizes = [];
            $cupSizes = [];
            $possibleTopValues = [];

            foreach ($product['tops'] as $top) {
                if (!empty($top['band']) && !empty($top['cup'])) {
                    $bandSizes[] = $top['band'];
                    $cupSizes[] = $top['cup'];
                    $possibleTopValues[] = [
                        'band size' => $top['band'],
                        'cup size' => $top['cup']
                    ];
                }
            }

            $bandSizes = array_values(array_unique($bandSizes));
            $cupSizes = array_values(array_unique($cupSizes));

            $transformedProduct[] = [
                'type' => 'group',
                'name' => 'top',
                'fields' => [
                    [
                        'label' => 'band size',
                        'values' => $bandSizes
                    ],
                    [
                        'label' => 'cup size',
                        'values' => $cupSizes
                    ]
                ],
                'possible_values' => $possibleTopValues
            ];
        }

        if (isset($product['bottoms'])) {
            $bottomSizes = [];
            $possibleBottomValues = [];

            foreach ($product['bottoms'] as $bottom) {
                if (!empty($bottom['size'])) {
                    $bottomSizes[] = $bottom['size'];
                    $possibleBottomValues[] = [
                        'size' => $bottom['size']
                    ];
                }
            }

            $bottomSizes = array_values(array_unique($bottomSizes));

            $transformedProduct[] = [
                'type' => 'group',
                'name' => 'bottom',
                'fields' => [
                    [
                        'label' => 'size',
                        'values' => $bottomSizes
                    ]
                ],
                'possible_values' => $possibleBottomValues
            ];
        }

        if (!empty($transformedProduct)) {
            $productsData[$productId] = $transformedProduct;
        }
    }


    file_put_contents(__DIR__ . '/products.json', json_encode($productsData, JSON_PRETTY_PRINT));
}

productData();
