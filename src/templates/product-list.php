<div class="container">
    <div class="py-4 d-flex">
        <div class="flex-fill">
            <h2 class="m-0">Product List</h2>
        </div>
        <div class="row g-2">
            <div class="col-auto">
                <button id="mass-delete" class="btn btn-danger px-4">MASS DELETE</button>
            </div>
            <div class="col-auto">
                <a href="/product/saveNew" class="btn btn-primary px-4">ADD</a>
            </div>
        </div>
    </div>

    <div id="product-list" class="row">
        <?php foreach($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-fill">
                                <h5 class="card-title"><?= $product['name'] ?></h5>
                            </div>
                            <div>
                                <input class="delete-checkbox form-check-input" type="checkbox" value="<?= $product['sku'] ?>" />
                            </div>
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
