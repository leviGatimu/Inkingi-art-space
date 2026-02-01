<?php include 'includes/header.php'; ?>

<div class="sub-header">
    <h1>OUR ART COLLECTION</h1>
</div>

<main class="container">
    
    <div style="text-align: center; max-width: 700px; margin: 0 auto 50px auto;" class="reveal">
        <p style="font-size: 1.1rem; color: #555;">"Our collection represents the vibrant soul of Rwanda. From abstract expressions to realistic portrayals of village life, every piece tells a story waiting to be heard."</p>
        <p style="font-weight: bold; margin-top: 10px;">- Jean Paul, Head Curator</p>
    </div>

    <div class="filter-container reveal">
        <button class="filter-btn active">All Art</button>
        <button class="filter-btn">Paintings</button>
        <button class="filter-btn">Sculpture</button>
        <button class="filter-btn">Textile & Weaving</button>
        <button class="filter-btn">Photography</button>
    </div>

    <section class="gallery-dark reveal" style="border-radius: 10px;">
        <div class="artwork-grid">
            <?php
            $artworks = [
                ['img' => 'https://images.unsplash.com/photo-1579783902614-a3fb39279c15?w=800', 'price' => '100'],
                ['img' => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?w=800', 'price' => '250'],
                ['img' => 'https://images.unsplash.com/photo-1549887552-93f8efb0818e?w=800', 'price' => '180'],
                ['img' => 'https://images.unsplash.com/photo-1578321272176-b7bbc0679853?w=800', 'price' => '300'],
                ['img' => 'https://images.unsplash.com/photo-1580130379745-139979752741?w=800', 'price' => '150'],
                ['img' => 'https://images.unsplash.com/photo-1577083552431-6e5fd01aa342?w=800', 'price' => '400'],
            ];
            foreach ($artworks as $art) {
                echo "
                <div class='art-card'>
                    <img src='{$art['img']}'>
                    <div class='price-tag'>£{$art['price']}</div>
                </div>";
            }
            ?>
        </div>
    </section>

    <section class="reveal" style="margin-top: 80px;">
        <h2 class="section-header">WHAT COLLECTORS SAY</h2>
        <div class="testimonial-grid">
            <div class="testimonial-card">
                <p>"I bought the 'Village Life' piece last month. The shipping was careful, and the art looks even better on my wall than on the website."</p>
                <span class="client-name">David K.</span>
            </div>
            <div class="testimonial-card">
                <p>"Ikingi is my go-to place for authentic gifts. Supporting local artists feels great, and the quality is world-class."</p>
                <span class="client-name">Sarah M.</span>
            </div>
            <div class="testimonial-card">
                <p>"A hidden gem in Kigali. The staff explained the history behind every painting. Truly an immersive experience."</p>
                <span class="client-name">James R.</span>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>