<?php

function productData()
{
    $data = file_get_contents('https://gw-services.prd.adoreme.com/v2/feeds/veesual');
    $products = json_decode($data, true);

    $productsData = [];
    $productNames = [];

    foreach ($products as $product) {
        $productId = $product['id'];
        $productName = str_replace(' Plus', '', $product['title']);
        if (!isset($productNames[$productName])) {
            $productNames[$productName] = [];
        }
        $productNames[$productId][] = $product;
    }

    $sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '1X', '2X', '3X', '4X', '38', '40', '42', '44', '46'];
    $cupOrder = ['A', 'B', 'C', 'D', 'DD', 'DDD', 'G', 'H', 'I'];

    $sizeWeights = array_flip($sizeOrder);
    $cupWeights = array_flip($cupOrder);

    foreach ($productNames as $productName => $productVersions) {
        $transformedProduct = [];

        $bandSizes = [];
        $cupSizes = [];
        $topSizes = [];
        $bottomSizes = [];
        $possibleTopValues = [];
        $possibleBottomValues = [];

        foreach ($productVersions as $product) {
            if (isset($product['tops'])) {
                foreach ($product['tops'] as $top) {
                    if (!empty($top['band']) && !empty($top['cup'])) {
                        $bandSizes[] = $top['band'];
                        $cupSizes[] = $top['cup'];
                        $possibleTopValues[] = [
                            'band size' => $top['band'],
                            'cup size' => $top['cup']
                        ];
                    } elseif (!empty($top['size'])) {
                        $topSizes[] = $top['size'];
                        $possibleTopValues[] = [
                            'size' => $top['size']
                        ];
                    }
                }
            }

            if (isset($product['bottoms'])) {
                foreach ($product['bottoms'] as $bottom) {
                    if (!empty($bottom['size'])) {
                        $bottomSizes[] = $bottom['size'];
                        $possibleBottomValues[] = [
                            'size' => $bottom['size']
                        ];
                    }
                }
            }
        }

        $bandSizes = array_values(array_unique($bandSizes));
        $cupSizes = array_values(array_unique($cupSizes));
        $topSizes = array_values(array_unique($topSizes));
        $bottomSizes = array_values(array_unique($bottomSizes));

        usort($bandSizes, function ($a, $b) use ($sizeWeights) {
            if (!isset($sizeWeights[$a]) && !isset($sizeWeights[$b])) {
                return 0;
            } elseif (!isset($sizeWeights[$a])) {
                return 1;
            } elseif (!isset($sizeWeights[$b])) {
                return -1;
            } else {
                return $sizeWeights[$a] - $sizeWeights[$b];
            }
        });

        usort($cupSizes, function ($a, $b) use ($cupWeights) {
            if (!isset($cupWeights[$a]) && !isset($cupWeights[$b])) {
                return 0;
            } elseif (!isset($cupWeights[$a])) {
                return 1;
            } elseif (!isset($cupWeights[$b])) {
                return -1;
            } else {
                return $cupWeights[$a] - $cupWeights[$b];
            }
        });

        usort($topSizes, function ($a, $b) use ($sizeWeights) {
            if (!isset($sizeWeights[$a]) && !isset($sizeWeights[$b])) {
                return 0;
            } elseif (!isset($sizeWeights[$a])) {
                return 1;
            } elseif (!isset($sizeWeights[$b])) {
                return -1;
            } else {
                return $sizeWeights[$a] - $sizeWeights[$b];
            }
        });

        usort($bottomSizes, function ($a, $b) use ($sizeWeights) {
            if (!isset($sizeWeights[$a]) && !isset($sizeWeights[$b])) {
                return 0;
            } elseif (!isset($sizeWeights[$a])) {
                return 1;
            } elseif (!isset($sizeWeights[$b])) {
                return -1;
            } else {
                return $sizeWeights[$a] - $sizeWeights[$b];
            }
        });

        if (!empty($bandSizes) && !empty($cupSizes)) {
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
        } elseif (!empty($topSizes)) {
            $transformedProduct[] = [
                'type' => 'group',
                'name' => 'top',
                'fields' => [
                    [
                        'label' => 'size',
                        'values' => $topSizes
                    ]
                ],
                'possible_values' => $possibleTopValues
            ];
        }

        if (!empty($bottomSizes)) {
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
            $productsData[$productName] = $transformedProduct;
        }
    }

    file_put_contents(__DIR__ . '/products.json', json_encode($productsData, JSON_PRETTY_PRINT));
}

productData();
