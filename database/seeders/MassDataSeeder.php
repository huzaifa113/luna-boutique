<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ReturnExchange;
use App\Models\ReturnExchangeItem;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MassDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('Seeding massive dataset...');

        // ====================================================================
        // 1. CATEGORIES – 50 total (10 parents + 40 children)
        // ====================================================================
        $parentCategories = [
            ['name' => 'Women', 'slug' => 'women', 'description' => 'Women\'s fashion and apparel.'],
            ['name' => 'Men', 'slug' => 'men', 'description' => 'Men\'s fashion and apparel.'],
            ['name' => 'Kids', 'slug' => 'kids', 'description' => 'Kids\' clothing and accessories.'],
            ['name' => 'Home & Living', 'slug' => 'home-living', 'description' => 'Home decor and living essentials.'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Fashion accessories and adornments.'],
            ['name' => 'Footwear', 'slug' => 'footwear', 'description' => 'Shoes, sneakers, and footwear.'],
            ['name' => 'Sports & Outdoors', 'slug' => 'sports-outdoors', 'description' => 'Sports gear and outdoor equipment.'],
            ['name' => 'Beauty & Health', 'slug' => 'beauty-health', 'description' => 'Beauty products and health essentials.'],
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Gadgets, devices, and electronics.'],
            ['name' => 'Books & Media', 'slug' => 'books-media', 'description' => 'Books, music, and media.'],
        ];

        $subCategories = [
            'women'       => ['Dresses', 'Tops', 'Bottoms', 'Outerwear', 'Swimwear', 'Activewear', 'Lingerie', 'Maternity'],
            'men'         => ['Shirts', 'Pants', 'Suits', 'Jackets', 'Activewear', 'Underwear', 'Sleepwear', 'Swimwear'],
            'kids'        => ['Girls (2-8)', 'Boys (2-8)', 'Girls (8-14)', 'Boys (8-14)', 'Babies (0-2)', 'Toys'],
            'home-living' => ['Furniture', 'Kitchen', 'Bedding', 'Bath', 'Decor', 'Lighting', 'Storage', 'Gardening'],
            'accessories' => ['Bags & Wallets', 'Jewelry', 'Watches', 'Sunglasses', 'Belts', 'Hats & Scarves', 'Socks & Hosiery'],
            'footwear'    => ['Sneakers', 'Boots', 'Sandals', 'Formal Shoes', 'Slippers', 'Sports Shoes', 'Heels & Flats'],
            'sports-outdoors' => ['Gym Equipment', 'Yoga', 'Cycling', 'Camping', 'Fishing', 'Team Sports', 'Running'],
            'beauty-health'   => ['Skincare', 'Makeup', 'Hair Care', 'Fragrance', 'Personal Care', 'Supplements', 'Essential Oils'],
            'electronics'     => ['Mobile Phones', 'Laptops', 'Headphones', 'Smartwatches', 'Cameras', 'Tablets', 'Speakers', 'Chargers'],
            'books-media'     => ['Fiction', 'Non-Fiction', 'Children\'s Books', 'Self-Help', 'Comics', 'Stationery'],
        ];

        $categoryMap = collect();

        foreach ($parentCategories as $parent) {
            $parentModel = Category::create([
                'name' => $parent['name'],
                'slug' => $parent['slug'],
                'description' => $parent['description'],
                'is_active' => true,
                'sort_order' => 0,
            ]);
            $categoryMap[$parent['slug']] = $parentModel;

            $children = $subCategories[$parent['slug']] ?? [];
            foreach ($children as $i => $childName) {
                $childSlug = Str::slug($childName);
                $child = Category::create([
                    'parent_id' => $parentModel->id,
                    'name' => $childName,
                    'slug' => "{$parent['slug']}-" . $childSlug,
                    'description' => "{$childName} - {$parent['name']} collection.",
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ]);
                $categoryMap["{$parent['slug']}-{$childSlug}"] = $child;
            }
        }

        $this->command?->info('Created ' . Category::count() . ' categories.');

        // ====================================================================
        // 2. BRANDS – 25 brands
        // ====================================================================
        $brandList = [
            ['name' => 'Acme Apparel', 'slug' => 'acme-apparel'],
            ['name' => 'Urban Edge', 'slug' => 'urban-edge'],
            ['name' => 'Luxe Home', 'slug' => 'luxe-home'],
            ['name' => 'Voyager Co', 'slug' => 'voyager-co'],
            ['name' => 'Nova Style', 'slug' => 'nova-style'],
            ['name' => 'Zenith Wear', 'slug' => 'zenith-wear'],
            ['name' => 'Pulse Active', 'slug' => 'pulse-active'],
            ['name' => 'Bloom Beauty', 'slug' => 'bloom-beauty'],
            ['name' => 'TechSphere', 'slug' => 'techsphere'],
            ['name' => 'PageTurner Books', 'slug' => 'pageturner-books'],
            ['name' => 'Sole Mates', 'slug' => 'sole-mates'],
            ['name' => 'EcoLiving', 'slug' => 'ecoliving'],
            ['name' => 'Craft & Co', 'slug' => 'craft-co'],
            ['name' => 'Radiance Cosmetics', 'slug' => 'radiance-cosmetics'],
            ['name' => 'Iron Flex', 'slug' => 'iron-flex'],
            ['name' => 'Little Wonders', 'slug' => 'little-wonders'],
            ['name' => 'Heritage Home', 'slug' => 'heritage-home'],
            ['name' => 'CloudNine', 'slug' => 'cloudnine'],
            ['name' => 'Bold Steps', 'slug' => 'bold-steps'],
            ['name' => 'Fresh Brew', 'slug' => 'fresh-brew'],
            ['name' => 'Timeless Classics', 'slug' => 'timeless-classics'],
            ['name' => 'GreenLeaf Organics', 'slug' => 'greenleaf-organics'],
            ['name' => 'SmartGear', 'slug' => 'smartgear'],
            ['name' => 'Coastal Vibes', 'slug' => 'coastal-vibes'],
            ['name' => 'Mountain Trek', 'slug' => 'mountain-trek'],
        ];

        $brandMap = collect();
        foreach ($brandList as $b) {
            $brand = Brand::create([
                'name' => $b['name'],
                'slug' => $b['slug'],
                'description' => "{$b['name']} – premium quality products.",
                'is_active' => true,
            ]);
            $brandMap[$b['slug']] = $brand;
        }

        $this->command?->info('Created ' . Brand::count() . ' brands.');

        // ====================================================================
        // 3. PRODUCTS – 200 products organized by categories
        // ====================================================================
        $productTemplates = [
            // Women – Dresses
            ['name' => 'Floral Maxi Dress', 'slug' => 'floral-maxi-dress', 'sku' => 'WDR-001', 'category' => 'women-dresses', 'brand' => 'acme-apparel', 'price' => 89.99, 'cost' => 42.00, 'compare' => 119.99, 'stock' => 35, 'featured' => true],
            ['name' => 'Little Black Dress', 'slug' => 'little-black-dress', 'sku' => 'WDR-002', 'category' => 'women-dresses', 'brand' => 'urban-edge', 'price' => 129.00, 'cost' => 60.00, 'compare' => 169.00, 'stock' => 28, 'featured' => true],
            ['name' => 'Summer Sundress', 'slug' => 'summer-sundress', 'sku' => 'WDR-003', 'category' => 'women-dresses', 'brand' => 'nova-style', 'price' => 49.99, 'cost' => 22.00, 'compare' => 0, 'stock' => 50, 'featured' => false],
            ['name' => 'Evening Gown', 'slug' => 'evening-gown', 'sku' => 'WDR-004', 'category' => 'women-dresses', 'brand' => 'timeless-classics', 'price' => 249.99, 'cost' => 120.00, 'compare' => 299.99, 'stock' => 12, 'featured' => true],
            ['name' => 'Wrap Dress', 'slug' => 'wrap-dress', 'sku' => 'WDR-005', 'category' => 'women-dresses', 'brand' => 'acme-apparel', 'price' => 79.99, 'cost' => 38.00, 'compare' => 0, 'stock' => 42, 'featured' => false],
            ['name' => 'Shirt Dress', 'slug' => 'shirt-dress', 'sku' => 'WDR-006', 'category' => 'women-dresses', 'brand' => 'urban-edge', 'price' => 69.99, 'cost' => 32.00, 'compare' => 89.99, 'stock' => 30, 'featured' => false],
            ['name' => 'Bodycon Dress', 'slug' => 'bodycon-dress', 'sku' => 'WDR-007', 'category' => 'women-dresses', 'brand' => 'nova-style', 'price' => 54.99, 'cost' => 25.00, 'compare' => 0, 'stock' => 48, 'featured' => false],

            // Women – Tops
            ['name' => 'Cotton T-Shirt', 'slug' => 'cotton-t-shirt', 'sku' => 'WTP-001', 'category' => 'women-tops', 'brand' => 'acme-apparel', 'price' => 29.99, 'cost' => 12.00, 'compare' => 0, 'stock' => 100, 'featured' => false],
            ['name' => 'Silk Blouse', 'slug' => 'silk-blouse', 'sku' => 'WTP-002', 'category' => 'women-tops', 'brand' => 'zenith-wear', 'price' => 89.00, 'cost' => 42.00, 'compare' => 110.00, 'stock' => 25, 'featured' => true],
            ['name' => 'Cropped Sweater', 'slug' => 'cropped-sweater', 'sku' => 'WTP-003', 'category' => 'women-tops', 'brand' => 'nova-style', 'price' => 45.00, 'cost' => 20.00, 'compare' => 0, 'stock' => 38, 'featured' => false],
            ['name' => 'Lace Camisole', 'slug' => 'lace-camisole', 'sku' => 'WTP-004', 'category' => 'women-tops', 'brand' => 'coastal-vibes', 'price' => 34.99, 'cost' => 15.00, 'compare' => 0, 'stock' => 55, 'featured' => false],
            ['name' => 'Turtleneck Sweater', 'slug' => 'turtleneck-sweater', 'sku' => 'WTP-005', 'category' => 'women-tops', 'brand' => 'acme-apparel', 'price' => 59.99, 'cost' => 28.00, 'compare' => 75.00, 'stock' => 32, 'featured' => false],

            // Women – Bottoms
            ['name' => 'High-Waist Jeans', 'slug' => 'high-waist-jeans', 'sku' => 'WBT-001', 'category' => 'women-bottoms', 'brand' => 'urban-edge', 'price' => 69.99, 'cost' => 32.00, 'compare' => 89.99, 'stock' => 45, 'featured' => true],
            ['name' => 'Wide Leg Trousers', 'slug' => 'wide-leg-trousers', 'sku' => 'WBT-002', 'category' => 'women-bottoms', 'brand' => 'zenith-wear', 'price' => 79.00, 'cost' => 38.00, 'compare' => 0, 'stock' => 22, 'featured' => false],
            ['name' => 'Leather Leggings', 'slug' => 'leather-leggings', 'sku' => 'WBT-003', 'category' => 'women-bottoms', 'brand' => 'urban-edge', 'price' => 59.99, 'cost' => 28.00, 'compare' => 79.99, 'stock' => 30, 'featured' => false],
            ['name' => 'Pleated Skirt', 'slug' => 'pleated-skirt', 'sku' => 'WBT-004', 'category' => 'women-bottoms', 'brand' => 'acme-apparel', 'price' => 49.99, 'cost' => 22.00, 'compare' => 0, 'stock' => 40, 'featured' => false],
            ['name' => 'Denim Shorts', 'slug' => 'denim-shorts', 'sku' => 'WBT-005', 'category' => 'women-bottoms', 'brand' => 'coastal-vibes', 'price' => 39.99, 'cost' => 18.00, 'compare' => 0, 'stock' => 65, 'featured' => false],

            // Women – Outerwear
            ['name' => 'Classic Trench Coat', 'slug' => 'classic-trench-coat', 'sku' => 'WOW-001', 'category' => 'women-outerwear', 'brand' => 'timeless-classics', 'price' => 199.00, 'cost' => 95.00, 'compare' => 259.00, 'stock' => 18, 'featured' => true],
            ['name' => 'Puffer Jacket', 'slug' => 'puffer-jacket', 'sku' => 'WOW-002', 'category' => 'women-outerwear', 'brand' => 'acme-apparel', 'price' => 129.99, 'cost' => 60.00, 'compare' => 0, 'stock' => 25, 'featured' => false],
            ['name' => 'Leather Biker Jacket', 'slug' => 'leather-biker-jacket', 'sku' => 'WOW-003', 'category' => 'women-outerwear', 'brand' => 'urban-edge', 'price' => 189.00, 'cost' => 95.00, 'compare' => 239.00, 'stock' => 15, 'featured' => true],
            ['name' => 'Wool Peacoat', 'slug' => 'wool-peacoat', 'sku' => 'WOW-004', 'category' => 'women-outerwear', 'brand' => 'timeless-classics', 'price' => 229.00, 'cost' => 110.00, 'compare' => 289.00, 'stock' => 14, 'featured' => true],

            // Women – Activewear
            ['name' => 'Yoga Leggings', 'slug' => 'yoga-leggings', 'sku' => 'WAC-001', 'category' => 'women-activewear', 'brand' => 'pulse-active', 'price' => 54.99, 'cost' => 25.00, 'compare' => 0, 'stock' => 70, 'featured' => true],
            ['name' => 'Sports Bra', 'slug' => 'sports-bra', 'sku' => 'WAC-002', 'category' => 'women-activewear', 'brand' => 'pulse-active', 'price' => 39.99, 'cost' => 18.00, 'compare' => 0, 'stock' => 85, 'featured' => false],
            ['name' => 'Running Shorts', 'slug' => 'running-shorts', 'sku' => 'WAC-003', 'category' => 'women-activewear', 'brand' => 'pulse-active', 'price' => 34.99, 'cost' => 15.00, 'compare' => 0, 'stock' => 60, 'featured' => false],
            ['name' => 'Zip-Up Hoodie', 'slug' => 'zip-up-hoodie', 'sku' => 'WAC-004', 'category' => 'women-activewear', 'brand' => 'iron-flex', 'price' => 64.99, 'cost' => 30.00, 'compare' => 79.99, 'stock' => 40, 'featured' => false],

            // Men – Shirts
            ['name' => 'Oxford Button-Down', 'slug' => 'oxford-button-down-m', 'sku' => 'MSH-001', 'category' => 'men-shirts', 'brand' => 'acme-apparel', 'price' => 69.99, 'cost' => 32.00, 'compare' => 0, 'stock' => 55, 'featured' => false],
            ['name' => 'Linen Shirt', 'slug' => 'linen-shirt', 'sku' => 'MSH-002', 'category' => 'men-shirts', 'brand' => 'coastal-vibes', 'price' => 59.00, 'cost' => 28.00, 'compare' => 75.00, 'stock' => 40, 'featured' => true],
            ['name' => 'Denim Shirt', 'slug' => 'denim-shirt', 'sku' => 'MSH-003', 'category' => 'men-shirts', 'brand' => 'urban-edge', 'price' => 64.99, 'cost' => 30.00, 'compare' => 0, 'stock' => 35, 'featured' => false],
            ['name' => 'Polo Shirt', 'slug' => 'polo-shirt', 'sku' => 'MSH-004', 'category' => 'men-shirts', 'brand' => 'nova-style', 'price' => 49.99, 'cost' => 22.00, 'compare' => 0, 'stock' => 80, 'featured' => false],
            ['name' => 'Flannel Shirt', 'slug' => 'flannel-shirt', 'sku' => 'MSH-005', 'category' => 'men-shirts', 'brand' => 'mountain-trek', 'price' => 54.99, 'cost' => 25.00, 'compare' => 0, 'stock' => 45, 'featured' => false],
            ['name' => 'Graphic Tee', 'slug' => 'graphic-tee', 'sku' => 'MSH-006', 'category' => 'men-shirts', 'brand' => 'nova-style', 'price' => 29.99, 'cost' => 12.00, 'compare' => 0, 'stock' => 120, 'featured' => false],

            // Men – Pants
            ['name' => 'Slim Fit Chinos', 'slug' => 'slim-fit-chinos-m', 'sku' => 'MPN-001', 'category' => 'men-pants', 'brand' => 'urban-edge', 'price' => 65.00, 'cost' => 30.00, 'compare' => 0, 'stock' => 60, 'featured' => false],
            ['name' => 'Cargo Pants', 'slug' => 'cargo-pants', 'sku' => 'MPN-002', 'category' => 'men-pants', 'brand' => 'mountain-trek', 'price' => 59.99, 'cost' => 28.00, 'compare' => 0, 'stock' => 40, 'featured' => false],
            ['name' => 'Tailored Trousers', 'slug' => 'tailored-trousers', 'sku' => 'MPN-003', 'category' => 'men-pants', 'brand' => 'zenith-wear', 'price' => 89.00, 'cost' => 42.00, 'compare' => 115.00, 'stock' => 25, 'featured' => true],
            ['name' => 'Jogger Sweatpants', 'slug' => 'jogger-sweatpants', 'sku' => 'MPN-004', 'category' => 'men-pants', 'brand' => 'pulse-active', 'price' => 44.99, 'cost' => 20.00, 'compare' => 0, 'stock' => 75, 'featured' => false],
            ['name' => 'Straight Leg Jeans', 'slug' => 'straight-leg-jeans', 'sku' => 'MPN-005', 'category' => 'men-pants', 'brand' => 'acme-apparel', 'price' => 69.99, 'cost' => 32.00, 'compare' => 89.99, 'stock' => 50, 'featured' => false],

            // Men – Suits & Jackets
            ['name' => 'Wool Suit Jacket', 'slug' => 'wool-suit-jacket', 'sku' => 'MSU-001', 'category' => 'men-suits', 'brand' => 'zenith-wear', 'price' => 349.00, 'cost' => 170.00, 'compare' => 449.00, 'stock' => 10, 'featured' => true],
            ['name' => 'Blazer', 'slug' => 'blazer', 'sku' => 'MSU-002', 'category' => 'men-suits', 'brand' => 'timeless-classics', 'price' => 189.00, 'cost' => 90.00, 'compare' => 239.00, 'stock' => 18, 'featured' => false],
            ['name' => 'Dress Pants', 'slug' => 'dress-pants', 'sku' => 'MSU-003', 'category' => 'men-suits', 'brand' => 'zenith-wear', 'price' => 119.00, 'cost' => 55.00, 'compare' => 0, 'stock' => 22, 'featured' => false],
            ['name' => 'Bomber Jacket', 'slug' => 'bomber-jacket', 'sku' => 'MSU-004', 'category' => 'men-suits', 'brand' => 'urban-edge', 'price' => 129.00, 'cost' => 60.00, 'compare' => 159.00, 'stock' => 28, 'featured' => true],

            // Men – Activewear
            ['name' => 'Performance Tee', 'slug' => 'performance-tee', 'sku' => 'MAC-001', 'category' => 'men-activewear', 'brand' => 'iron-flex', 'price' => 34.99, 'cost' => 15.00, 'compare' => 0, 'stock' => 90, 'featured' => false],
            ['name' => 'Gym Shorts', 'slug' => 'gym-shorts', 'sku' => 'MAC-002', 'category' => 'men-activewear', 'brand' => 'pulse-active', 'price' => 29.99, 'cost' => 12.00, 'compare' => 0, 'stock' => 100, 'featured' => false],
            ['name' => 'Compression Shirt', 'slug' => 'compression-shirt', 'sku' => 'MAC-003', 'category' => 'men-activewear', 'brand' => 'iron-flex', 'price' => 39.99, 'cost' => 18.00, 'compare' => 0, 'stock' => 55, 'featured' => false],
            ['name' => 'Track Jacket', 'slug' => 'track-jacket', 'sku' => 'MAC-004', 'category' => 'men-activewear', 'brand' => 'pulse-active', 'price' => 74.99, 'cost' => 35.00, 'compare' => 0, 'stock' => 35, 'featured' => false],

            // Kids
            ['name' => 'Printed T-Shirt (Girls)', 'slug' => 'printed-tshirt-girls', 'sku' => 'KID-001', 'category' => 'kids-girls-2-8', 'brand' => 'little-wonders', 'price' => 19.99, 'cost' => 8.00, 'compare' => 0, 'stock' => 100, 'featured' => false],
            ['name' => 'Denim Dress', 'slug' => 'denim-dress-kids', 'sku' => 'KID-002', 'category' => 'kids-girls-2-8', 'brand' => 'little-wonders', 'price' => 34.99, 'cost' => 15.00, 'compare' => 0, 'stock' => 40, 'featured' => true],
            ['name' => 'Graphic Tee (Boys)', 'slug' => 'graphic-tee-boys', 'sku' => 'KID-003', 'category' => 'kids-boys-2-8', 'brand' => 'little-wonders', 'price' => 18.99, 'cost' => 7.00, 'compare' => 0, 'stock' => 110, 'featured' => false],
            ['name' => 'Cargo Joggers (Boys)', 'slug' => 'cargo-joggers-boys', 'sku' => 'KID-004', 'category' => 'kids-boys-2-8', 'brand' => 'little-wonders', 'price' => 28.99, 'cost' => 12.00, 'compare' => 0, 'stock' => 60, 'featured' => false],
            ['name' => 'Unisex Baby Bodysuit (3-Pack)', 'slug' => 'baby-bodysuit-3pack', 'sku' => 'KID-005', 'category' => 'kids-babies-0-2', 'brand' => 'little-wonders', 'price' => 24.99, 'cost' => 10.00, 'compare' => 0, 'stock' => 80, 'featured' => false],
            ['name' => 'Baby Romper', 'slug' => 'baby-romper', 'sku' => 'KID-006', 'category' => 'kids-babies-0-2', 'brand' => 'little-wonders', 'price' => 22.99, 'cost' => 9.00, 'compare' => 0, 'stock' => 65, 'featured' => false],
            ['name' => 'Skater Dress (8-14)', 'slug' => 'skater-dress-8-14', 'sku' => 'KID-007', 'category' => 'kids-girls-8-14', 'brand' => 'nova-style', 'price' => 39.99, 'cost' => 18.00, 'compare' => 0, 'stock' => 35, 'featured' => true],
            ['name' => 'Hoodie (8-14)', 'slug' => 'hoodie-8-14', 'sku' => 'KID-008', 'category' => 'kids-boys-8-14', 'brand' => 'nova-style', 'price' => 44.99, 'cost' => 20.00, 'compare' => 0, 'stock' => 45, 'featured' => false],
            ['name' => 'Educational Building Blocks', 'slug' => 'building-blocks', 'sku' => 'KID-009', 'category' => 'kids-toys', 'brand' => 'little-wonders', 'price' => 29.99, 'cost' => 12.00, 'compare' => 39.99, 'stock' => 50, 'featured' => false],
            ['name' => 'Stuffed Teddy Bear', 'slug' => 'teddy-bear', 'sku' => 'KID-010', 'category' => 'kids-toys', 'brand' => 'little-wonders', 'price' => 24.99, 'cost' => 10.00, 'compare' => 0, 'stock' => 70, 'featured' => true],

            // Footwear – Sneakers
            ['name' => 'Classic White Sneakers', 'slug' => 'classic-white-sneakers', 'sku' => 'FSN-001', 'category' => 'footwear-sneakers', 'brand' => 'sole-mates', 'price' => 89.99, 'cost' => 42.00, 'compare' => 119.99, 'stock' => 45, 'featured' => true],
            ['name' => 'Running Sneakers', 'slug' => 'running-sneakers', 'sku' => 'FSN-002', 'category' => 'footwear-sneakers', 'brand' => 'bold-steps', 'price' => 129.99, 'cost' => 60.00, 'compare' => 0, 'stock' => 32, 'featured' => true],
            ['name' => 'Canvas Sneakers', 'slug' => 'canvas-sneakers', 'sku' => 'FSN-003', 'category' => 'footwear-sneakers', 'brand' => 'sole-mates', 'price' => 49.99, 'cost' => 22.00, 'compare' => 0, 'stock' => 70, 'featured' => false],
            ['name' => 'High Top Sneakers', 'slug' => 'high-top-sneakers', 'sku' => 'FSN-004', 'category' => 'footwear-sneakers', 'brand' => 'bold-steps', 'price' => 99.99, 'cost' => 48.00, 'compare' => 129.99, 'stock' => 25, 'featured' => false],

            // Footwear – Boots
            ['name' => 'Leather Ankle Boots', 'slug' => 'leather-ankle-boots', 'sku' => 'FBT-001', 'category' => 'footwear-boots', 'brand' => 'bold-steps', 'price' => 149.00, 'cost' => 70.00, 'compare' => 189.00, 'stock' => 20, 'featured' => true],
            ['name' => 'Winter Snow Boots', 'slug' => 'winter-snow-boots', 'sku' => 'FBT-002', 'category' => 'footwear-boots', 'brand' => 'mountain-trek', 'price' => 129.99, 'cost' => 60.00, 'compare' => 0, 'stock' => 28, 'featured' => false],
            ['name' => 'Chelsea Boots', 'slug' => 'chelsea-boots', 'sku' => 'FBT-003', 'category' => 'footwear-boots', 'brand' => 'timeless-classics', 'price' => 159.00, 'cost' => 78.00, 'compare' => 199.00, 'stock' => 18, 'featured' => true],

            // Footwear – Sandals & Heels
            ['name' => 'Leather Sandals', 'slug' => 'leather-sandals', 'sku' => 'FSD-001', 'category' => 'footwear-sandals', 'brand' => 'coastal-vibes', 'price' => 59.99, 'cost' => 28.00, 'compare' => 0, 'stock' => 55, 'featured' => false],
            ['name' => 'Stiletto Heels', 'slug' => 'stiletto-heels', 'sku' => 'FHL-001', 'category' => 'footwear-heels-flats', 'brand' => 'bold-steps', 'price' => 89.99, 'cost' => 42.00, 'compare' => 119.99, 'stock' => 30, 'featured' => true],
            ['name' => 'Ballet Flats', 'slug' => 'ballet-flats', 'sku' => 'FHL-002', 'category' => 'footwear-heels-flats', 'brand' => 'sole-mates', 'price' => 49.99, 'cost' => 22.00, 'compare' => 0, 'stock' => 60, 'featured' => false],
            ['name' => 'Wedge Sandals', 'slug' => 'wedge-sandals', 'sku' => 'FSD-002', 'category' => 'footwear-sandals', 'brand' => 'coastal-vibes', 'price' => 69.99, 'cost' => 32.00, 'compare' => 0, 'stock' => 35, 'featured' => false],

            // Accessories – Bags & Wallets
            ['name' => 'Leather Tote Bag', 'slug' => 'leather-tote', 'sku' => 'ABG-001', 'category' => 'accessories-bags-wallets', 'brand' => 'voyager-co', 'price' => 159.00, 'cost' => 75.00, 'compare' => 199.00, 'stock' => 18, 'featured' => true],
            ['name' => 'Crossbody Bag', 'slug' => 'crossbody-bag', 'sku' => 'ABG-002', 'category' => 'accessories-bags-wallets', 'brand' => 'voyager-co', 'price' => 89.99, 'cost' => 42.00, 'compare' => 0, 'stock' => 35, 'featured' => false],
            ['name' => 'Minimalist Wallet', 'slug' => 'minimalist-wallet', 'sku' => 'ABG-003', 'category' => 'accessories-bags-wallets', 'brand' => 'craft-co', 'price' => 39.99, 'cost' => 18.00, 'compare' => 0, 'stock' => 80, 'featured' => false],
            ['name' => 'Canvas Backpack', 'slug' => 'canvas-backpack', 'sku' => 'ABG-004', 'category' => 'accessories-bags-wallets', 'brand' => 'voyager-co', 'price' => 64.99, 'cost' => 30.00, 'compare' => 0, 'stock' => 45, 'featured' => false],
            ['name' => 'Clutch Evening Bag', 'slug' => 'clutch-evening-bag', 'sku' => 'ABG-005', 'category' => 'accessories-bags-wallets', 'brand' => 'voyager-co', 'price' => 49.99, 'cost' => 22.00, 'compare' => 69.99, 'stock' => 25, 'featured' => true],

            // Accessories – Jewelry & Watches
            ['name' => 'Gold Hoop Earrings', 'slug' => 'gold-hoop-earrings', 'sku' => 'AJW-001', 'category' => 'accessories-jewelry', 'brand' => 'craft-co', 'price' => 34.99, 'cost' => 15.00, 'compare' => 0, 'stock' => 65, 'featured' => false],
            ['name' => 'Pearl Necklace', 'slug' => 'pearl-necklace', 'sku' => 'AJW-002', 'category' => 'accessories-jewelry', 'brand' => 'craft-co', 'price' => 59.99, 'cost' => 28.00, 'compare' => 79.99, 'stock' => 20, 'featured' => true],
            ['name' => 'Silver Bracelet', 'slug' => 'silver-bracelet', 'sku' => 'AJW-003', 'category' => 'accessories-jewelry', 'brand' => 'craft-co', 'price' => 44.99, 'cost' => 20.00, 'compare' => 0, 'stock' => 40, 'featured' => false],
            ['name' => 'Analog Watch (Chrono)', 'slug' => 'analog-watch-chrono', 'sku' => 'AWT-001', 'category' => 'accessories-watches', 'brand' => 'cloudnine', 'price' => 199.00, 'cost' => 95.00, 'compare' => 259.00, 'stock' => 15, 'featured' => true],
            ['name' => 'Smart Watch', 'slug' => 'smart-watch', 'sku' => 'AWT-002', 'category' => 'accessories-watches', 'brand' => 'smartgear', 'price' => 249.99, 'cost' => 120.00, 'compare' => 299.99, 'stock' => 22, 'featured' => true],
            ['name' => 'Leather Strap Watch', 'slug' => 'leather-strap-watch', 'sku' => 'AWT-003', 'category' => 'accessories-watches', 'brand' => 'cloudnine', 'price' => 129.00, 'cost' => 60.00, 'compare' => 0, 'stock' => 30, 'featured' => false],

            // Accessories – Sunglasses & Hats
            ['name' => 'Aviator Sunglasses', 'slug' => 'aviator-sunglasses', 'sku' => 'ASS-001', 'category' => 'accessories-sunglasses', 'brand' => 'voyager-co', 'price' => 79.00, 'cost' => 35.00, 'compare' => 0, 'stock' => 50, 'featured' => false],
            ['name' => 'Wayfarer Sunglasses', 'slug' => 'wayfarer-sunglasses', 'sku' => 'ASS-002', 'category' => 'accessories-sunglasses', 'brand' => 'voyager-co', 'price' => 69.99, 'cost' => 32.00, 'compare' => 89.99, 'stock' => 40, 'featured' => true],
            ['name' => 'Wide Brim Hat', 'slug' => 'wide-brim-hat', 'sku' => 'AHT-001', 'category' => 'accessories-hats-scarves', 'brand' => 'coastal-vibes', 'price' => 34.99, 'cost' => 15.00, 'compare' => 0, 'stock' => 35, 'featured' => false],
            ['name' => 'Cashmere Scarf', 'slug' => 'cashmere-scarf', 'sku' => 'AHT-002', 'category' => 'accessories-hats-scarves', 'brand' => 'voyager-co', 'price' => 49.99, 'cost' => 22.00, 'compare' => 0, 'stock' => 30, 'featured' => false],

            // Home & Living – Furniture & Decor
            ['name' => 'Accent Armchair', 'slug' => 'accent-armchair', 'sku' => 'HLF-001', 'category' => 'home-living-furniture', 'brand' => 'luxe-home', 'price' => 399.00, 'cost' => 190.00, 'compare' => 499.00, 'stock' => 8, 'featured' => true],
            ['name' => 'Coffee Table (Oak)', 'slug' => 'coffee-table-oak', 'sku' => 'HLF-002', 'category' => 'home-living-furniture', 'brand' => 'heritage-home', 'price' => 259.00, 'cost' => 120.00, 'compare' => 0, 'stock' => 12, 'featured' => false],
            ['name' => 'Bookshelf (3-Tier)', 'slug' => 'bookshelf-3tier', 'sku' => 'HLF-003', 'category' => 'home-living-furniture', 'brand' => 'heritage-home', 'price' => 149.00, 'cost' => 70.00, 'compare' => 189.00, 'stock' => 16, 'featured' => false],
            ['name' => 'Ceramic Vase Set', 'slug' => 'ceramic-vase-set', 'sku' => 'HLD-001', 'category' => 'home-living-decor', 'brand' => 'luxe-home', 'price' => 54.99, 'cost' => 25.00, 'compare' => 0, 'stock' => 28, 'featured' => false],
            ['name' => 'Scented Candle Collection', 'slug' => 'scented-candle-collection', 'sku' => 'HLD-002', 'category' => 'home-living-decor', 'brand' => 'luxe-home', 'price' => 39.99, 'cost' => 18.00, 'compare' => 0, 'stock' => 55, 'featured' => true],
            ['name' => 'Abstract Wall Art', 'slug' => 'abstract-wall-art', 'sku' => 'HLD-003', 'category' => 'home-living-decor', 'brand' => 'heritage-home', 'price' => 89.99, 'cost' => 42.00, 'compare' => 119.99, 'stock' => 15, 'featured' => true],

            // Home & Living – Kitchen & Bedding
            ['name' => 'Stainless Steel Cookware Set', 'slug' => 'cookware-set', 'sku' => 'HLK-001', 'category' => 'home-living-kitchen', 'brand' => 'ecoliving', 'price' => 199.99, 'cost' => 95.00, 'compare' => 259.99, 'stock' => 14, 'featured' => true],
            ['name' => 'Bamboo Cutting Board', 'slug' => 'bamboo-cutting-board', 'sku' => 'HLK-002', 'category' => 'home-living-kitchen', 'brand' => 'ecoliving', 'price' => 29.99, 'cost' => 12.00, 'compare' => 0, 'stock' => 60, 'featured' => false],
            ['name' => 'Cotton Sheet Set (400TC)', 'slug' => 'cotton-sheet-set', 'sku' => 'HLB-001', 'category' => 'home-living-bedding', 'brand' => 'luxe-home', 'price' => 89.99, 'cost' => 42.00, 'compare' => 0, 'stock' => 35, 'featured' => false],
            ['name' => 'Down Comforter', 'slug' => 'down-comforter', 'sku' => 'HLB-002', 'category' => 'home-living-bedding', 'brand' => 'luxe-home', 'price' => 149.00, 'cost' => 70.00, 'compare' => 189.00, 'stock' => 20, 'featured' => true],
            ['name' => 'Bath Towel Set (6-Pack)', 'slug' => 'bath-towel-set', 'sku' => 'HLBTH-001', 'category' => 'home-living-bath', 'brand' => 'ecoliving', 'price' => 44.99, 'cost' => 20.00, 'compare' => 0, 'stock' => 50, 'featured' => false],

            // Sports & Outdoors
            ['name' => 'Yoga Mat (Premium)', 'slug' => 'yoga-mat-premium', 'sku' => 'SOY-001', 'category' => 'sports-outdoors-yoga', 'brand' => 'pulse-active', 'price' => 49.99, 'cost' => 22.00, 'compare' => 0, 'stock' => 65, 'featured' => true],
            ['name' => 'Adjustable Dumbbells', 'slug' => 'adjustable-dumbbells', 'sku' => 'SOG-001', 'category' => 'sports-outdoors-gym-equipment', 'brand' => 'iron-flex', 'price' => 179.99, 'cost' => 85.00, 'compare' => 229.99, 'stock' => 12, 'featured' => true],
            ['name' => 'Resistance Bands Set', 'slug' => 'resistance-bands-set', 'sku' => 'SOG-002', 'category' => 'sports-outdoors-gym-equipment', 'brand' => 'iron-flex', 'price' => 24.99, 'cost' => 10.00, 'compare' => 0, 'stock' => 80, 'featured' => false],
            ['name' => 'Camping Tent (4-Person)', 'slug' => 'camping-tent-4person', 'sku' => 'SOC-001', 'category' => 'sports-outdoors-camping', 'brand' => 'mountain-trek', 'price' => 199.99, 'cost' => 95.00, 'compare' => 259.99, 'stock' => 10, 'featured' => true],
            ['name' => 'Insulated Water Bottle', 'slug' => 'insulated-water-bottle', 'sku' => 'SOC-002', 'category' => 'sports-outdoors-camping', 'brand' => 'mountain-trek', 'price' => 34.99, 'cost' => 15.00, 'compare' => 0, 'stock' => 100, 'featured' => false],
            ['name' => 'Hiking Backpack (40L)', 'slug' => 'hiking-backpack-40l', 'sku' => 'SOC-003', 'category' => 'sports-outdoors-camping', 'brand' => 'mountain-trek', 'price' => 89.99, 'cost' => 42.00, 'compare' => 0, 'stock' => 22, 'featured' => false],
            ['name' => 'Soccer Ball (Official Size)', 'slug' => 'soccer-ball', 'sku' => 'SOT-001', 'category' => 'sports-outdoors-team-sports', 'brand' => 'pulse-active', 'price' => 34.99, 'cost' => 15.00, 'compare' => 0, 'stock' => 40, 'featured' => false],

            // Beauty & Health
            ['name' => 'Vitamin C Serum', 'slug' => 'vitamin-c-serum', 'sku' => 'BHS-001', 'category' => 'beauty-health-skincare', 'brand' => 'bloom-beauty', 'price' => 34.99, 'cost' => 15.00, 'compare' => 44.99, 'stock' => 60, 'featured' => true],
            ['name' => 'Moisturizing Face Cream', 'slug' => 'face-cream-moisturizer', 'sku' => 'BHS-002', 'category' => 'beauty-health-skincare', 'brand' => 'radiance-cosmetics', 'price' => 28.99, 'cost' => 12.00, 'compare' => 0, 'stock' => 70, 'featured' => false],
            ['name' => 'Matte Lipstick Collection', 'slug' => 'matte-lipstick-collection', 'sku' => 'BHM-001', 'category' => 'beauty-health-makeup', 'brand' => 'bloom-beauty', 'price' => 24.99, 'cost' => 10.00, 'compare' => 0, 'stock' => 90, 'featured' => false],
            ['name' => 'Eyeliner Palette', 'slug' => 'eyeliner-palette', 'sku' => 'BHM-002', 'category' => 'beauty-health-makeup', 'brand' => 'radiance-cosmetics', 'price' => 19.99, 'cost' => 8.00, 'compare' => 0, 'stock' => 85, 'featured' => false],
            ['name' => 'Argan Hair Oil', 'slug' => 'argan-hair-oil', 'sku' => 'BHH-001', 'category' => 'beauty-health-hair-care', 'brand' => 'greenleaf-organics', 'price' => 22.99, 'cost' => 10.00, 'compare' => 0, 'stock' => 55, 'featured' => false],
            ['name' => 'Eau de Parfum (50ml)', 'slug' => 'eau-de-parfum-50ml', 'sku' => 'BHF-001', 'category' => 'beauty-health-fragrance', 'brand' => 'bloom-beauty', 'price' => 79.99, 'cost' => 38.00, 'compare' => 99.99, 'stock' => 25, 'featured' => true],
            ['name' => 'Organic Multivitamins', 'slug' => 'organic-multivitamins', 'sku' => 'BHSM-001', 'category' => 'beauty-health-supplements', 'brand' => 'greenleaf-organics', 'price' => 24.99, 'cost' => 10.00, 'compare' => 0, 'stock' => 100, 'featured' => false],

            // Electronics
            ['name' => 'Wireless Bluetooth Headphones', 'slug' => 'bluetooth-headphones', 'sku' => 'ELH-001', 'category' => 'electronics-headphones', 'brand' => 'techsphere', 'price' => 149.99, 'cost' => 70.00, 'compare' => 199.99, 'stock' => 30, 'featured' => true],
            ['name' => 'Noise Cancelling Earbuds', 'slug' => 'noise-cancelling-earbuds', 'sku' => 'ELH-002', 'category' => 'electronics-headphones', 'brand' => 'smartgear', 'price' => 89.99, 'cost' => 42.00, 'compare' => 0, 'stock' => 45, 'featured' => true],
            ['name' => 'Smartphone Case (Silicone)', 'slug' => 'smartphone-case-silicone', 'sku' => 'ELM-001', 'category' => 'electronics-mobile-phones', 'brand' => 'techsphere', 'price' => 19.99, 'cost' => 8.00, 'compare' => 0, 'stock' => 150, 'featured' => false],
            ['name' => 'Portable Power Bank (20000mAh)', 'slug' => 'power-bank-20000mah', 'sku' => 'ELC-001', 'category' => 'electronics-chargers', 'brand' => 'smartgear', 'price' => 39.99, 'cost' => 18.00, 'compare' => 0, 'stock' => 60, 'featured' => false],
            ['name' => 'Bluetooth Speaker (Waterproof)', 'slug' => 'bluetooth-speaker-waterproof', 'sku' => 'ELS-001', 'category' => 'electronics-speakers', 'brand' => 'techsphere', 'price' => 59.99, 'cost' => 28.00, 'compare' => 79.99, 'stock' => 35, 'featured' => false],
            ['name' => 'USB-C Hub (7-in-1)', 'slug' => 'usb-c-hub-7in1', 'sku' => 'ELL-001', 'category' => 'electronics-laptops', 'brand' => 'smartgear', 'price' => 44.99, 'cost' => 20.00, 'compare' => 0, 'stock' => 40, 'featured' => false],
            ['name' => 'Laptop Stand (Aluminum)', 'slug' => 'laptop-stand-aluminum', 'sku' => 'ELL-002', 'category' => 'electronics-laptops', 'brand' => 'techsphere', 'price' => 34.99, 'cost' => 15.00, 'compare' => 0, 'stock' => 50, 'featured' => false],

            // Books & Media
            ['name' => 'The Art of Mindfulness', 'slug' => 'art-of-mindfulness', 'sku' => 'BKNF-001', 'category' => 'books-media-non-fiction', 'brand' => 'pageturner-books', 'price' => 24.99, 'cost' => 12.00, 'compare' => 0, 'stock' => 80, 'featured' => true],
            ['name' => 'Whispers in the Wind (Novel)', 'slug' => 'whispers-in-the-wind', 'sku' => 'BKF-001', 'category' => 'books-media-fiction', 'brand' => 'pageturner-books', 'price' => 19.99, 'cost' => 9.00, 'compare' => 0, 'stock' => 60, 'featured' => false],
            ['name' => 'Adventures of Captain Z (Kids Book)', 'slug' => 'adventures-captain-z', 'sku' => 'BKC-001', 'category' => 'books-media-childrens-books', 'brand' => 'pageturner-books', 'price' => 14.99, 'cost' => 6.00, 'compare' => 0, 'stock' => 100, 'featured' => false],
            ['name' => 'The 5 AM Club', 'slug' => 'the-5am-club', 'sku' => 'BKS-003', 'category' => 'books-media-self-help', 'brand' => 'pageturner-books', 'price' => 22.99, 'cost' => 11.00, 'compare' => 0, 'stock' => 55, 'featured' => true],
            ['name' => 'Classic Comic Collection Vol.1', 'slug' => 'comic-collection-vol1', 'sku' => 'BKM-001', 'category' => 'books-media-comics', 'brand' => 'pageturner-books', 'price' => 29.99, 'cost' => 14.00, 'compare' => 0, 'stock' => 40, 'featured' => false],
            ['name' => 'Premium Notebook (Lined)', 'slug' => 'premium-notebook-lined', 'sku' => 'BKS-001', 'category' => 'books-media-stationery', 'brand' => 'craft-co', 'price' => 16.99, 'cost' => 7.00, 'compare' => 0, 'stock' => 120, 'featured' => false],
            ['name' => 'Art Sketchbook (A4)', 'slug' => 'art-sketchbook-a4', 'sku' => 'BKS-002', 'category' => 'books-media-stationery', 'brand' => 'craft-co', 'price' => 19.99, 'cost' => 9.00, 'compare' => 0, 'stock' => 75, 'featured' => false],
        ];

        $imageUrls = [
            'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1515036664887-7e284b6c3ddd?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1603006905003-be475563bc59?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1616486029423-aaa4789e8c9a?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1581783898377-1c85bf937427?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1597074866922-dc1e7c5e1b0a?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1560343090-f0409e92791a?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1565905966750-5edff0be1d68?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=1200&q=80',
        ];

        $createdProducts = collect();

        foreach ($productTemplates as $data) {
            $catKey = $data['category'];
            $cat = $categoryMap->get($catKey);
            if (!$cat) {
                // fallback parent category
                $parentSlug = explode('-', $catKey)[0];
                $cat = $categoryMap->get($parentSlug) ?? $categoryMap->first();
            }

            $brand = $brandMap->get($data['brand']) ?? $brandMap->first();

            $product = Product::create([
                'category_id' => $cat->id,
                'brand_id' => $brand->id,
                'name' => $data['name'],
                'slug' => $data['slug'],
                'sku' => $data['sku'],
                'short_description' => "{$data['name']} – premium quality.",
                'description' => "Experience the finest quality with our {$data['name']}. Carefully crafted from premium materials, this piece combines style, comfort, and durability. Perfect for any occasion, it's designed to elevate your everyday look.",
                'price' => $data['price'],
                'cost_price' => $data['cost'],
                'compare_price' => $data['compare'] ?: null,
                'stock_quantity' => $data['stock'],
                'is_active' => true,
                'is_featured' => $data['featured'],
            ]);

            // Primary image
            $imgUrl = $imageUrls[array_rand($imageUrls)];
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $imgUrl,
                'alt_text' => $data['name'],
                'is_primary' => true,
                'sort_order' => 0,
            ]);

            // 1-2 additional images
            $extraCount = rand(1, 2);
            for ($j = 1; $j <= $extraCount; $j++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imageUrls[array_rand($imageUrls)],
                    'alt_text' => "{$data['name']} – view {$j}",
                    'is_primary' => false,
                    'sort_order' => $j,
                ]);
            }

            $createdProducts->push($product);
        }

        $this->command?->info('Created ' . $createdProducts->count() . ' products with images.');

        // ====================================================================
        // 4. USERS – 100 regular users + admin
        // ====================================================================
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ecomm.test',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'San Antonio', 'San Diego', 'Dallas', 'San Jose', 'Austin',
            'Jacksonville', 'Fort Worth', 'Columbus', 'Charlotte', 'Indianapolis', 'San Francisco', 'Seattle', 'Denver', 'Nashville', 'Portland',
            'Miami', 'Atlanta', 'Boston', 'Detroit', 'Minneapolis', 'Tampa', 'Orlando', 'St. Louis', 'Baltimore', 'Riverside'];

        $users = collect([$admin]);
        for ($i = 0; $i < 100; $i++) {
            $firstName = fake()->firstName();
            $lastName = fake()->lastName();
            $user = User::create([
                'name' => "{$firstName} {$lastName}",
                'email' => strtolower($firstName) . '.' . strtolower($lastName) . "{$i}@example.com",
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]);
            $users->push($user);
        }

        $this->command?->info('Created ' . $users->count() . ' users.');

        // ====================================================================
        // 5. ADDRESSES – 2-3 per user
        // ====================================================================
        $users->each(function ($user) use ($cities) {
            $addrCount = rand(2, 3);
            for ($i = 0; $i < $addrCount; $i++) {
                Address::create([
                    'user_id' => $user->id,
                    'first_name' => $user->name,
                    'last_name' => '',
                    'company' => $i === 0 ? null : fake()->company(),
                    'address_line1' => fake()->streetAddress(),
                    'address_line2' => $i === 0 ? null : fake()->secondaryAddress(),
                    'city' => $cities[array_rand($cities)],
                    'state' => fake()->state(),
                    'postal_code' => fake()->postcode(),
                    'country' => 'United States',
                    'phone' => fake()->phoneNumber(),
                    'is_default_billing' => $i === 0,
                    'is_default_shipping' => $i === 0,
                ]);
            }
        });

        $this->command?->info('Created addresses.');

        // ====================================================================
        // 6. COUPONS – 25 coupons with various types
        // ====================================================================
        $couponCodes = [
            ['code' => 'WELCOME10', 'type' => 'percent', 'value' => 10, 'min' => 50, 'max_disc' => 25, 'limit' => 200],
            ['code' => 'SAVE20', 'type' => 'percent', 'value' => 20, 'min' => 100, 'max_disc' => 50, 'limit' => 150],
            ['code' => 'FLAT15', 'type' => 'fixed', 'value' => 15, 'min' => 75, 'max_disc' => 15, 'limit' => 100],
            ['code' => 'FREESHIP', 'type' => 'percent', 'value' => 100, 'min' => 0, 'max_disc' => 10, 'limit' => 300],
            ['code' => 'SUMMER25', 'type' => 'percent', 'value' => 25, 'min' => 80, 'max_disc' => 60, 'limit' => 100],
            ['code' => 'VIP50', 'type' => 'fixed', 'value' => 50, 'min' => 200, 'max_disc' => 50, 'limit' => 50],
            ['code' => 'NEWUSER', 'type' => 'percent', 'value' => 15, 'min' => 0, 'max_disc' => 30, 'limit' => 500],
            ['code' => 'FLASH30', 'type' => 'percent', 'value' => 30, 'min' => 150, 'max_disc' => 75, 'limit' => 75],
            ['code' => 'FALL15', 'type' => 'percent', 'value' => 15, 'min' => 60, 'max_disc' => 35, 'limit' => 120],
            ['code' => 'WINTERWARM', 'type' => 'fixed', 'value' => 20, 'min' => 100, 'max_disc' => 20, 'limit' => 80],
            ['code' => 'SPRING20', 'type' => 'percent', 'value' => 20, 'min' => 90, 'max_disc' => 40, 'limit' => 90],
            ['code' => 'BDAY25', 'type' => 'percent', 'value' => 25, 'min' => 0, 'max_disc' => 50, 'limit' => 60],
            ['code' => 'LOYAL10', 'type' => 'percent', 'value' => 10, 'min' => 0, 'max_disc' => 20, 'limit' => 999],
            ['code' => 'CLEARANCE', 'type' => 'percent', 'value' => 40, 'min' => 200, 'max_disc' => 100, 'limit' => 40],
            ['code' => 'FAMILY20', 'type' => 'percent', 'value' => 20, 'min' => 120, 'max_disc' => 45, 'limit' => 70],
            ['code' => 'STUDENT15', 'type' => 'percent', 'value' => 15, 'min' => 30, 'max_disc' => 25, 'limit' => 200],
            ['code' => 'WEEKEND25', 'type' => 'percent', 'value' => 25, 'min' => 100, 'max_disc' => 55, 'limit' => 65],
            ['code' => 'SAVE5', 'type' => 'fixed', 'value' => 5, 'min' => 20, 'max_disc' => 5, 'limit' => 999],
            ['code' => 'BIGDEAL', 'type' => 'percent', 'value' => 35, 'min' => 250, 'max_disc' => 120, 'limit' => 30],
            ['code' => 'HOLIDAY20', 'type' => 'percent', 'value' => 20, 'min' => 75, 'max_disc' => 40, 'limit' => 150],
        ];

        foreach ($couponCodes as $c) {
            Coupon::create([
                'code' => $c['code'],
                'type' => $c['type'],
                'value' => $c['value'],
                'min_order_amount' => $c['min'],
                'max_discount' => $c['max_disc'],
                'usage_limit' => $c['limit'],
                'used' => 0,
                'expires_at' => now()->addMonths(rand(1, 6)),
                'is_active' => true,
            ]);
        }

        $this->command?->info('Created ' . Coupon::count() . ' coupons.');

        // ====================================================================
        // 7. REVIEWS – 5-10 per product => ~1500 reviews
        // ====================================================================
        $reviewTitles = [
            'Absolutely love it!', 'Great quality', 'Worth every penny', 'Exceeded expectations',
            'Perfect fit', 'Beautiful design', 'Highly recommend', 'Good value for money',
            'Stunning piece', 'Very comfortable', 'Looks even better in person', 'My new favorite',
            'Solid purchase', 'Elegant and timeless', 'Exactly as described', 'Five stars!',
            'Better than expected', 'Impressive craftsmanship', 'Would buy again', 'Absolutely gorgeous',
            'Perfect gift', 'Incredible quality', 'So happy with this', 'A must-have',
            'Exceptional product', 'Thrilled with my purchase', 'Classy and elegant', 'Superb quality',
        ];

        $reviewComments = [
            'The quality is outstanding and the fit is perfect. I\'ve received so many compliments!',
            'Really impressed with the craftsmanship. The material feels premium and the stitching is flawless.',
            'Bought this as a gift and they absolutely adored it. The packaging was beautiful too.',
            'I was hesitant at first but so glad I ordered. It looks amazing and the sizing is accurate.',
            'Great addition to my wardrobe. Versatile enough to dress up or down.',
            'The color is exactly as shown in the photos. Very happy with this purchase.',
            'Fast shipping and the product exceeded my expectations. Will definitely buy again.',
            'Nice quality for the price. Would recommend to anyone looking for something stylish.',
            'Perfect for everyday use. The material is durable and the design is timeless.',
            'Exceeded my expectations in every way. The attention to detail is remarkable.',
            'I\'ve been using this for weeks now and it still looks brand new. Highly durable.',
            'The customer service was excellent and the product arrived in perfect condition.',
            'This is my second purchase from this brand and they never disappoint.',
            'The quality speaks for itself. You can feel the premium materials just by touching it.',
            'I compared this with other brands and this one wins hands down on quality and price.',
            'Absolutely stunning! Received so many compliments the first time I wore it.',
        ];

        $users->each(function ($user) use ($createdProducts, $reviewTitles, $reviewComments) {
            // Each user reviews 10-25 products
            $reviewCount = rand(10, 25);
            $productsToReview = $createdProducts->random(min($reviewCount, $createdProducts->count()));

            $productsToReview->each(function ($product) use ($user, $reviewTitles, $reviewComments) {
                Review::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rating' => rand(3, 5),
                    'title' => $reviewTitles[array_rand($reviewTitles)],
                    'comment' => $reviewComments[array_rand($reviewComments)],
                    'status' => 'approved',
                ]);
            });
        });

        $this->command?->info('Created reviews.');

        // ====================================================================
        // 8. ORDERS & ORDER ITEMS – 8-15 per user => ~1000 orders
        // ====================================================================
        $allCoupons = Coupon::all();

        $users->each(function ($user) use ($allCoupons, $cities, $createdProducts) {
            $orderCount = rand(8, 15);
            $addresses = $user->addresses;

            for ($i = 0; $i < $orderCount; $i++) {
                $address = $addresses->random();
                $status = fake()->randomElement(Order::STATUSES);
                $paymentStatus = $status === Order::STATUS_DELIVERED ? Order::PAYMENT_STATUS_PAID : fake()->randomElement(Order::PAYMENT_STATUSES);
                $paymentMethod = fake()->randomElement(Order::PAYMENT_METHODS);

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'ORD-' . strtoupper(fake()->bothify('######-????-####')),
                    'shipping_address_id' => $address->id,
                    'billing_address_id' => $address->id,
                    'coupon_id' => rand(1, 3) === 1 ? $allCoupons->random()->id : null,
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'payment_method' => $paymentMethod,
                    'subtotal' => 0,
                    'discount' => 0,
                    'tax' => 0,
                    'shipping' => 0,
                    'total' => 0,
                    'notes' => rand(1, 4) === 1 ? fake()->sentence() : null,
                ]);

                $itemsTotal = 0;
                $productCount = rand(1, 5);
                $orderProducts = $createdProducts->random($productCount);

                $orderProducts->each(function ($product) use ($order, &$itemsTotal) {
                    $quantity = rand(1, 3);
                    $totalPrice = round($quantity * $product->price, 2);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                        'total_price' => $totalPrice,
                    ]);

                    $itemsTotal += $totalPrice;
                });

                $subtotal = round($itemsTotal, 2);
                $discount = ($order->coupon_id && rand(0, 1)) ? round($subtotal * rand(5, 20) / 100, 2) : 0;
                $tax = round($subtotal * 0.08, 2);
                $shipping = $subtotal > 100 ? 0 : 9.99;
                $total = round(($subtotal + $tax + $shipping) - $discount, 2);

                $order->update([
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'shipping' => $shipping,
                    'total' => $total,
                ]);
            }
        });

        $this->command?->info('Created orders and order items.');

        // ====================================================================
        // 9. CART ITEMS – 60% of users have items in cart
        // ====================================================================
        $usersWithCart = $users->random(floor($users->count() * 0.6));
        $usersWithCart->each(function ($user) use ($createdProducts) {
            $itemCount = rand(1, 5);
            $cartProducts = $createdProducts->random($itemCount);

            $cartProducts->each(function ($product) use ($user) {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3),
                ]);
            });
        });

        $this->command?->info('Created cart items.');

        // ====================================================================
        // 10. WISHLISTS – 50% of users have wishlist items
        // ====================================================================
        $usersWithWishlist = $users->random(floor($users->count() * 0.5));
        $usersWithWishlist->each(function ($user) use ($createdProducts) {
            $itemCount = rand(3, 8);
            $wishProducts = $createdProducts->random($itemCount);

            $wishProducts->each(function ($product) use ($user) {
                Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);
            });
        });

        $this->command?->info('Created wishlist items.');

        // ====================================================================
        // 11. RETURN/EXCHANGES – for ~10% of delivered orders
        // ====================================================================
        $deliveredOrders = Order::where('status', Order::STATUS_DELIVERED)->get();
        $returnOrders = $deliveredOrders->random(min(floor($deliveredOrders->count() * 0.1), 50));

        $reasons = ['defective', 'wrong_item', 'not_as_described', 'size_issue', 'changed_mind'];

        $returnOrders->each(function ($order) use ($reasons) {
            $orderItems = $order->items;
            if ($orderItems->isEmpty()) return;

            $reason = $reasons[array_rand($reasons)];
            $status = fake()->randomElement(ReturnExchange::STATUSES);

            $returnExchange = ReturnExchange::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'status' => $status,
                'reason' => $reason,
                'details' => $reason === 'defective' ? 'Product arrived with visible damage.' :
                            ($reason === 'wrong_item' ? 'Received wrong size/color.' :
                            ($reason === 'size_issue' ? 'Does not fit as expected.' :
                            'Changed my mind about this purchase.')),
                'refund_amount' => $status === ReturnExchange::STATUS_APPROVED ? $order->total : 0,
                'requested_at' => now()->subDays(rand(1, 30)),
                'admin_processed_at' => in_array($status, [ReturnExchange::STATUS_APPROVED, ReturnExchange::STATUS_REJECTED])
                    ? now()->subDays(rand(0, 14)) : null,
            ]);

            // Add 1-3 items to return
            $itemsToReturn = $orderItems->random(min(rand(1, count($orderItems)), count($orderItems)));
            $itemsToReturn->each(function ($item) use ($returnExchange) {
                ReturnExchangeItem::create([
                    'return_exchange_id' => $returnExchange->id,
                    'order_item_id' => $item->id,
                    'quantity' => min($item->quantity, rand(1, $item->quantity)),
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ]);
            });
        });

        $this->command?->info('Created return/exchanges.');

        $this->command?->info('=== MASS DATA SEEDING COMPLETE ===');
        $this->command?->info('Categories: ' . Category::count());
        $this->command?->info('Brands: ' . Brand::count());
        $this->command?->info('Products: ' . Product::count());
        $this->command?->info('Users: ' . User::count());
        $this->command?->info('Addresses: ' . Address::count());
        $this->command?->info('Orders: ' . Order::count());
        $this->command?->info('Order Items: ' . OrderItem::count());
        $this->command?->info('Reviews: ' . Review::count());
        $this->command?->info('Coupons: ' . Coupon::count());
        $this->command?->info('Cart Items: ' . CartItem::count());
        $this->command?->info('Wishlists: ' . Wishlist::count());
        $this->command?->info('Return/Exchanges: ' . ReturnExchange::count());
    }
}