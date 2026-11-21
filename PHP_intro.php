<?php
// 1. STRICT TYPES
declare(strict_types=1);
// --------------- MODULE 3 ----------- //

// 2. A multidimensional array 
$albums = [
    '1ST EP: COLOR OUTSIDE THE LINES (2025)' => ['price' => 32.34, 'stock' => 342, 'quantity' => 2],
    '2ND EP: GREENGREEN (2026)'  => ['price' => 68.66, 'stock' => 363, 'quantity' => 3],
    '3RD EP: GREENGREEN_PLAYEREXTENDED (2026)' => ['price' => 88.75, 'stock' => 132, 'quantity' => 5], 
    '4TH EP: RED (2027)' => ['price' => 99.99, 'stock' => 50, 'quantity' => 10],
    '5TH EP: BLUE (2028)' => ['price' => 120.00, 'stock' => 25, 'quantity' => 12],
    '6TH EP: YELLOW (2029)' => ['price' => 150.00, 'stock' => 10, 'quantity' => 10]
];

// 3. A global variable is declared to hold the tax rate.
$tax_rate = 30;

// 4 & 5. Function to check stock level using a ternary operator.
function get_reorder_message(int $stock_level): string
{
    return ($stock_level < 10) ? 'Yes' : 'No';
}

// 6. & 7 Function to calculate the total value of the stock for a product.
function get_total_value(float $price, int $quantity): float
{
    return $price * $quantity;
}

// 8. Function called get_tax_due() is defined. 
function get_tax_due(float $price, int $quantity, int $tax_rate) : float {
    return ($price * $quantity) * $tax_rate/100;
}

// ------------ MODULE 1 & 2 --------------//
// ===== VARIABLES =====
$storeName = 'K-POP CORTIS STORE';
$customer = 'FAN';
$membership = 'gold';        // try 'silver' or 'regular'
$taxRate = 0.12;             // 12% tax
$shippingFee = 5;
$freeShippingMinimum = 150;
$photocardEvery = 50;        // 1 free photocard for every $50 spent

// ===== ARRAYS =====
// Index 0 = 1st EP, 1 = 2nd EP, 2 = 3rd EP
$titles = [
    '1ST EP: COLOR OUTSIDE THE LINES (2025)',
    '2ND EP: GREENGREEN (2026)',
    '3RD EP: GREENGREEN_PLAYEREXTENDED (2026)'
];
$prices = [32.34, 68.66, 88.75];
$quantities = [2, 1, 1];     // what the customer wants to buy
$stock = [10, 3, 1];         // copies left in the store

// ===== FOR LOOP: compute every line total, subtotal and item count =====
$lineTotals = [];
$subtotal = 0;
$itemsPurchased = 0;

for ($i = 0; $i < count($titles); $i++) {
    // IF: nobody can buy more copies than the store has
    if ($quantities[$i] > $stock[$i]) {
        $quantities[$i] = $stock[$i];
    }
    $lineTotals[$i] = $prices[$i] * $quantities[$i];
    $subtotal += $lineTotals[$i];
    $itemsPurchased += $quantities[$i];
}

// ===== SWITCH: membership level =====
switch ($membership) {
    case 'gold':
        $memberRate = 0.05;
        $memberLabel = 'Gold member';
        break;
    case 'silver':
        $memberRate = 0.02;
        $memberLabel = 'Silver member';
        break;
    default:
        $memberRate = 0;
        $memberLabel = 'Regular';
}

// ===== IF / ELSEIF / ELSE: bundle discount tiers (percent kept as a string) =====
if ($subtotal >= 300) {
    $discountPercent = '15';
} elseif ($subtotal >= 150) {
    $discountPercent = '10';
} elseif ($subtotal >= 100) {
    $discountPercent = '5';
} else {
    $discountPercent = '0';
}

// ===== LOGICAL OPERATOR =====
$qualifies = $subtotal >= 100 && $itemsPurchased >= 3;

// ===== TYPE JUGGLING =====
$discountRate = $discountPercent / 100;                   // string becomes a number
$bundleDiscount = $subtotal * $discountRate * $qualifies; // true = 1, false = 0
$memberDiscount = $subtotal * $memberRate;
$discount = $bundleDiscount + $memberDiscount;
$afterDiscount = $subtotal - $discount;

// ===== IF / ELSE: shipping =====
if ($afterDiscount >= $freeShippingMinimum) {
    $shipping = 0;
} else {
    $shipping = $shippingFee;
}

// ===== EXPRESSIONS =====
$tax = $afterDiscount * $taxRate;
$total = $afterDiscount + $shipping + $tax;

// ===== WHILE LOOP: 1 free photocard for every $50 spent =====
$photocards = 0;
$remaining = $afterDiscount;
while ($remaining >= $photocardEvery) {
    $photocards++;
    $remaining -= $photocardEvery;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title><?php echo $storeName; ?></title>
</head>
<body>
    <h1><?php echo $storeName; ?><img src="img/logo.png" alt="logo"></h1>
    <h2>Welcome COER <?php echo $customer; ?> <span class="badge"><?php echo $memberLabel; ?></span></h2>
    <p>As you proceed for the payment, below is the result of your purchase:</p>

    <table>
        <thead>
            <tr>
                <th>PRODUCT</th>
                <th>STOCK</th>
                <th>RE-ORDER</th>
                <th>TOTAL VALUE</th>
                <th>TAX DUE</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($albums as $product_name => $data) : ?>
                <tr>
                    <td><?= $product_name ?></td>
                    <td><?= $data['stock']?></td>
                    <td><?= get_reorder_message($data['stock']) ?></td>
                    <td>₱<?= get_total_value($data['price'], $data['quantity']) ?></td>
                    <td>₱<?= get_tax_due($data['price'], $data['quantity'], $tax_rate) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="note">&copy; <?php echo $storeName; ?> - Thank you for shopping, <?php echo $customer; ?>!</p>
</body>
</html>