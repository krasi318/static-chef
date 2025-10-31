<?php
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Static Chef</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap Icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- SimpleLightbox plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <!-- Custom CSS for About Me section -->
        <style>
            .about-image {
                width: 100%;
                height: 400px;
                object-fit: cover;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                transition: transform 0.3s ease;
            }
            
            .about-image:hover {
                transform: scale(1.02);
            }
            
            .about-image-container {
                position: relative;
                overflow: hidden;
                border-radius: 15px;
            }
            
            #about {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                padding: 80px 0;
            }
            
            .display-4 {
                font-size: 3.5rem;
                font-weight: 700;
                color: #2c3e50 !important;
                margin-bottom: 2rem;
            }
            
            .lead {
                font-size: 1.25rem;
                font-weight: 400;
                line-height: 1.6;
                color: #495057;
            }
            
            .mb-4 {
                font-size: 1.1rem;
                line-height: 1.7;
                color: #6c757d;
            }
            
            @media (max-width: 768px) {
                .display-4 {
                    font-size: 2.5rem;
                }
                
                .about-image {
                    height: 300px;
                    margin-top: 2rem;
                }
            }
            
            /* Portfolio Carousel Styles */
            .portfolio-carousel-container {
                position: relative;
                max-width: 1200px;
                margin: 0 auto;
                overflow: hidden;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }
            
            .portfolio-carousel-track {
                display: flex;
                transition: transform 0.5s ease-in-out;
                gap: 20px;
                padding: 20px;
            }
            
            .portfolio-item {
                min-width: 300px;
                background: white;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s, box-shadow 0.3s;
                cursor: pointer;
            }
            
            .portfolio-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            }
            
            .portfolio-item img {
                width: 100%;
                height: 250px;
                object-fit: cover;
            }
            
            .portfolio-box-caption {
                background: linear-gradient(135deg, rgba(139, 69, 19, 0.9), rgba(160, 82, 45, 0.9));
                color: white;
                padding: 20px;
                text-align: center;
            }
            
            .project-category {
                font-size: 0.9rem;
                margin-bottom: 5px;
                opacity: 0.8;
            }
            
            .project-name {
                font-size: 1.2rem;
                font-weight: bold;
            }
            
            .show-all-item {
                min-width: 300px;
                background: linear-gradient(135deg, #8b4513, #a0522d);
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                text-decoration: none;
                transition: transform 0.3s, box-shadow 0.3s;
                cursor: pointer;
            }
            
            .show-all-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                color: white;
                text-decoration: none;
            }
            
            .show-all-content {
                text-align: center;
            }
            
            .show-all-icon {
                font-size: 3rem;
                margin-bottom: 15px;
            }
            
            .show-all-text {
                font-size: 1.5rem;
                font-weight: bold;
            }
            
            .carousel-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(139, 69, 19, 0.8);
                color: white;
                border: none;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                font-size: 20px;
                cursor: pointer;
                transition: all 0.3s;
                z-index: 10;
            }
            
            .carousel-nav:hover {
                background: rgba(139, 69, 19, 1);
                transform: translateY(-50%) scale(1.1);
            }
            
            .carousel-nav.prev {
                left: 20px;
            }
            
            .carousel-nav.next {
                right: 20px;
            }
            
            .carousel-dots {
                display: flex;
                justify-content: center;
                margin-top: 20px;
                gap: 10px;
            }
            
            .dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: #ddd;
                cursor: pointer;
                transition: background 0.3s;
            }
            
            .dot.active {
                background: #8b4513;
            }
            
            /* Navigation Menu Styles */
            .navbar-nav .nav-link {
                font-size: 1.2rem;
                font-weight: 600;
                padding: 0.75rem 1.5rem;
                margin: 0 0.5rem;
                color: #2c3e50;
                transition: all 0.3s ease;
                border-radius: 25px;
            }
            
            .navbar-nav .nav-link:hover {
                color: #8b4513;
                background-color: rgba(139, 69, 19, 0.1);
                transform: translateY(-2px);
            }
            
            .navbar-brand {
                font-size: 1.8rem;
                font-weight: 700;
                color: #2c3e50;
            }
            
            .navbar-brand:hover {
                color: #8b4513;
            }
            
            .navbar {
                padding: 1rem 0;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="#page-top">&lt; Static Chef /&gt;</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php#about">За мен</a></li>
                        <li class="nav-item"><a class="nav-link" href="menu.php">Моите рецепти</a></li>
                        <li class="nav-item"><a class="nav-link" href="community.php">Вашите рецепти</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Контакт</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container px-4 px-lg-5 h-100">
                <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-8 align-self-end">
                        <h1 class="text-white font-weight-bold">Рецепти, които наистина ще бъдат сготвени :)</h1>
                        <hr class="divider" />
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <a class="btn btn-primary btn-xl" href="menu.php">Към рецептите</a>
                    </div>
                </div>
            </div>
        </header>
        <!-- About-->
        <!-- <section class="page-section bg-primary" id="about">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="text-white mt-0">We've got what you need!</h2>
                        <hr class="divider divider-light" />
                        <p class="text-white-75 mb-4">Start Bootstrap has everything you need to get your new website up and running in no time! Choose one of our open source, free to download, and easy to use themes! No strings attached!</p>
                        <a class="btn btn-light btn-xl" href="#services">Get Started!</a>
                    </div>
                </div>
            </div>
        </section> -->
    
        <!-- Portfolio Carousel-->
        <section class="page-section bg-light" id="portfolio">
            <div class="container px-4 px-lg-6">
                <h2 class="text-center mt-0">Последните добавени рецепти :)</h2>
                <hr class="divider" />
                <div class="portfolio-carousel-container">
                    <button class="carousel-nav prev" id="portfolioPrevBtn">‹</button>
                    <button class="carousel-nav next" id="portfolioNextBtn">›</button>
                    <div class="portfolio-carousel-track" id="portfolioCarouselTrack">
                        <!-- Portfolio items will be dynamically added here -->
                    </div>
                </div>
                <div class="carousel-dots" id="portfolioDots">
                    <!-- Dots will be dynamically added here -->
                </div>
            </div>
        </section>
        
        <!-- Footer-->
        <footer class="bg-light py-5">
            <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Static Chef :))) 2025</div></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SimpleLightbox plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
        
        <!-- Portfolio Carousel JavaScript -->
        <script>
            class PortfolioCarousel {
                constructor() {
                    this.currentIndex = 0;
                    this.itemsPerView = 3;
                    this.maxIndex = 0;
                    this.portfolioItems = [];
                    this.loadItems();
                }

                async loadItems() {
                    try {
                        const response = await fetch('get_recipes.php', { headers: { 'Accept': 'application/json' } });
                        if (!response.ok) throw new Error('Network response was not ok');
                        const data = await response.json();
                        // Normalize data to expected shape for rendering
                        this.portfolioItems = (Array.isArray(data) ? data : []).map(item => ({
                            id: item.id,
                            title: item.title,
                            category: 'Recipe',
                            image_path: item.image_path,
                            link: item.link
                        }));
                    } catch (e) {
                        this.portfolioItems = [];
                    } finally {
                        this.init();
                    }
                }

                init() {
                    this.renderCarousel();
                    this.setupEventListeners();
                    this.updateDots();
                }

                renderCarousel() {
                    const track = document.getElementById('portfolioCarouselTrack');
                    const dotsContainer = document.getElementById('portfolioDots');
                    
                    // Render portfolio items
                    const itemsHtml = this.portfolioItems.map(item => `
                        <div class="portfolio-item" onclick="window.location.href='${item.link}'">
                            <img src="${item.image_path}" alt="${item.title}" />
                            <div class="portfolio-box-caption">
                                <div class="project-category">${item.category}</div>
                                <div class="project-name">${item.title}</div>
                            </div>
                        </div>
                    `).join('');

                    // Add "Show All" item
                    const showAllHtml = `
                        <div class="show-all-item" onclick="window.open('menu.php', '_self')">
                            <div class="show-all-content">
                                <div class="show-all-text">Show All Recipes</div>
                            </div>
                        </div>
                    `;

                    track.innerHTML = itemsHtml + showAllHtml;
                    
                    // Calculate max index based on current view
                    this.updateMaxIndex();
                    
                    // Render dots - only 2 pages
                    dotsContainer.innerHTML = Array.from({ length: 2 }, (_, i) => 
                        `<div class="dot ${i === 0 ? 'active' : ''}" data-index="${i}"></div>`
                    ).join('');

                    this.updateCarousel();
                }

                updateMaxIndex() {
                    // Only allow 2 scrolls: 3 items + "Show All" = 2 pages
                    this.maxIndex = this.itemsPerView; // Only one scroll to show "Show All"
                }

                updateCarousel() {
                    const track = document.getElementById('portfolioCarouselTrack');
                    const cardWidth = 320; // 300px + 20px gap
                    const translateX = -this.currentIndex * cardWidth;
                    track.style.transform = `translateX(${translateX}px)`;
                }

                updateDots() {
                    const dots = document.querySelectorAll('#portfolioDots .dot');
                    const currentPage = Math.floor(this.currentIndex / this.itemsPerView);
                    
                    dots.forEach((dot, index) => {
                        dot.classList.toggle('active', index === currentPage);
                    });
                }

                next() {
                    if (this.currentIndex < this.maxIndex) {
                        this.currentIndex += this.itemsPerView;
                        this.updateCarousel();
                        this.updateDots();
                    }
                }

                prev() {
                    if (this.currentIndex > 0) {
                        this.currentIndex = Math.max(0, this.currentIndex - this.itemsPerView);
                        this.updateCarousel();
                        this.updateDots();
                    }
                }

                goToSlide(index) {
                    this.currentIndex = index * this.itemsPerView;
                    this.updateCarousel();
                    this.updateDots();
                }

                setupEventListeners() {
                    document.getElementById('portfolioNextBtn').addEventListener('click', () => this.next());
                    document.getElementById('portfolioPrevBtn').addEventListener('click', () => this.prev());
                    
                    document.getElementById('portfolioDots').addEventListener('click', (e) => {
                        if (e.target.classList.contains('dot')) {
                            const index = parseInt(e.target.dataset.index);
                            this.goToSlide(index);
                        }
                    });

                    // Handle window resize
                    window.addEventListener('resize', () => {
                        this.updateMaxIndex();
                        this.currentIndex = Math.min(this.currentIndex, this.maxIndex);
                        this.updateCarousel();
                        this.updateDots();
                    });
                }
            }

            // Initialize the portfolio carousel
            document.addEventListener('DOMContentLoaded', () => {
                new PortfolioCarousel();
            });
        </script>
    </body>
    </html>

