<!-- PRODUCT GRID -->
@extends('layout.app')

@section('product-content')
    <!-- PRODUCT FEATURED -->
    <div class="product-featured">
        <div class="showcase-wrapper has-scrollbar">
            <div class="showcase-container">

                <div class="showcase">

                    <div class="showcase-banner">
                        <img src="{{ $menu->urlPhoto }}" alt="shampoo, conditioner & facewash packs"
                            class="showcase-img">
                    </div>

                    <div class="showcase-content">

                        <div class="showcase-rating">
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star"></ion-icon>
                            <ion-icon name="star-outline"></ion-icon>
                            <ion-icon name="star-outline"></ion-icon>
                        </div>

                        <a href="#">
                            <h3 class="showcase-title">{{ $menu->name }}</h3>
                        </a>

                        <p class="showcase-desc">
                            {{ $menu->description }}
                        </p>

                        <div class="price-box">
                            <p class="price">@currency($menu->price)</p>

                            {{-- <del>$200.00</del> --}}
                        </div>

                        <button class="add-cart-btn">add to cart</button>

                        <div class="showcase-status">
                            <div class="wrapper">
                                <p>
                                    already sold: <b>20</b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-main">
        <h2 class="title">Ulasan</h2>
        <div class="testimonials-box">
            <!-- TESTIMONIALS -->
            <div class="testimonial">
                <div class="testimonial-card">
                    <img src="{{ asset('assets/images/testimonial-1.jpg') }}" alt="alan doe" class="testimonial-banner"
                        width="80" height="80">

                    <p class="testimonial-name">Alan Doe</p>

                    <p class="testimonial-desc">
                        Lorem ipsum dolor sit amet consectetur Lorem ipsum
                        dolor dolor sit amet.
                    </p>

                </div>
            </div>
        </div>
    </div>

    <!-- PRODUCT GRID -->
    <div class="product-main">
        <h2 class="title">Lihat Juga Menu Ini</h2>
        <div class="product-grid">
            <div class="showcase">
                <div class="showcase-banner">
                    <img src="{{ asset('assets/images/products/jacket-3.jpg') }}" alt="Mens Winter Leathers Jackets" width="300"
                        class="product-img default">
                    <img src="{{ asset('assets/images/products/jacket-4.jpg') }}" alt="Mens Winter Leathers Jackets" width="300"
                        class="product-img hover">
                    <p class="showcase-badge">15%</p>
                    <div class="showcase-actions">
                        <button class="btn-action">
                            <ion-icon name="heart-outline"></ion-icon>
                        </button>
                        <button class="btn-action">
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class="btn-action">
                            <ion-icon name="repeat-outline"></ion-icon>
                        </button>
                        <button class="btn-action">
                            <ion-icon name="bag-add-outline"></ion-icon>
                        </button>
                    </div>
                </div>
                <div class="showcase-content">
                    <a href="#" class="showcase-category">jacket</a>
                    <a href="#">
                        <h3 class="showcase-title">Mens Winter Leathers Jackets</h3>
                    </a>
                    <div class="showcase-rating">
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star-outline"></ion-icon>
                        <ion-icon name="star-outline"></ion-icon>
                    </div>
                    <div class="price-box">
                        <p class="price">$48.00</p>
                        <del>$75.00</del>
                    </div>
                </div>
            </div>
            <div class="showcase">
                <div class="showcase-banner">
                    <img src="{{ asset('assets/images/products/shirt-1.jpg') }}" alt="Pure Garment Dyed Cotton Shirt"
                        class="product-img default" width="300">
                    <img src="{{ asset('assets/images/products/shirt-2.jpg') }}" alt="Pure Garment Dyed Cotton Shirt"
                        class="product-img hover" width="300">
                    <p class="showcase-badge angle black">sale</p>
                    <div class="showcase-actions">
                        <button class="btn-action">
                            <ion-icon name="heart-outline"></ion-icon>
                        </button>
                        <button class="btn-action">
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class="btn-action">
                            <ion-icon name="repeat-outline"></ion-icon>
                        </button>
                        <button class="btn-action">
                            <ion-icon name="bag-add-outline"></ion-icon>
                        </button>
                    </div>
                </div>
                <div class="showcase-content">
                    <a href="#" class="showcase-category">shirt</a>
                    <h3>
                        <a href="#" class="showcase-title">Pure Garment Dyed Cotton Shirt</a>
                    </h3>
                    <div class="showcase-rating">
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star-outline"></ion-icon>
                        <ion-icon name="star-outline"></ion-icon>
                    </div>
                    <div class="price-box">
                        <p class="price">$45.00</p>
                        <del>$56.00</del>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
