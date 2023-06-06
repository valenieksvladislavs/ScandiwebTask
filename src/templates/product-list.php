<div class="container">
    <div class="py-4 d-flex">
        <div class="flex-fill">
            <h2 class="m-0">Product List</h2>
        </div>
        <div class="row g-2">
            <div class="col-auto">
                <select id="products-actions" class="form-control">
                    <option value="mass-delete">Mass Delete Action</option>
                    <option value="add-product">Add Product Action</option>
                </select>
            </div>
            <div class="col-auto">
                <button id="apply-actions" class="btn btn-primary px-4">Apply</button>
            </div>
        </div>
    </div>

    <div id="product-list" class="row">
        <?php foreach($products as $product): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-fill">
                                <h5 class="card-title"><?= $product['name'] ?></h5>
                            </div>
                            <input class="form-check-input delete-checkbox" type="checkbox" value="<?= $product['sku'] ?>" />
                        </div>
                        <p class="card-text">
                            SKU: <?= $product['sku'] ?><br>
                            Price: <?= number_format($product['price'], 2) ?>$<br>
                            <?php if ($product['type'] === 'Book'): ?>
                                Weight: <?= $product['weight'] ?>KG
                            <?php elseif ($product['type'] === 'DVDDisc'): ?>
                                Size: <?= $product['size'] ?>MB
                            <?php elseif ($product['type'] === 'Furniture'): ?>
                                Dimension: <?= $product['height'] ?>x<?= $product['width'] ?>x<?= $product['length'] ?>mm
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script type="text/javascript" src="/js/product-list.min.js"></script>
