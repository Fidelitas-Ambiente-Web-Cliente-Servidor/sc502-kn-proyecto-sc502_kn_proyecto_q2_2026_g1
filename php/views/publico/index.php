    <!-- HERO -->
    <section class="hero hero-modern">
        <div class="hero-content">
            <h1 class="hero-title">
                Ellos no tienen voz,<br>
                pero <span>tú puedes</span><br>
                cambiar su mundo
            </h1>

            <p class="hero-description">
                Conectamos corazones y patitas. Encuentra a tu compañero ideal
                y cambia una vida para siempre.
            </p>

            <div class="hero-buttons">
                <a href="catalogo.php" class="btn btn-primary">Ver mascotas</a>
                <a href="#como-adoptar" class="btn btn-secondary">¿Cómo adoptar?</a>
            </div>
        </div>

        <div class="hero-image">
            <div class="hero-bubble">
                <img src="https://images.unsplash.com/photo-1517849845537-4d257902454a?auto=format&fit=crop&w=900&q=80" alt="Perro feliz">
            </div>

            <div class="hero-message">
                <p>Cada adopción es un nuevo comienzo</p>
            </div>
        </div>
    </section>

    <!-- ESTADÍSTICAS FLOTANTES -->
    <section class="impact-stats">
        <div class="impact-card">
            <div class="impact-item">
                <div>
                    <h3>1,245+</h3>
                    <p>Adopciones exitosas</p>
                </div>
            </div>

            <div class="impact-item">
                <div>
                    <h3>45</h3>
                    <p>Refugios aliados</p>
                </div>
            </div>

            <div class="impact-item">
                <div>
                    <h3>3,890+</h3>
                    <p>Familias felices</p>
                </div>
            </div>

            <div class="impact-item">
                <div>
                    <h3>98%</h3>
                    <p>Adopciones seguras</p>
                </div>
            </div>
        </div>
    </section>

    <!-- MASCOTAS DESTACADAS — CAROUSEL -->
    <section class="featured-pets">
        <div>
            <div class="section-header">
                <h2>Mascotas destacadas</h2>
                <a href="catalogo.php" class="ver-todos">Ver todas →</a>
            </div>

            <div class="carousel-wrapper">
                <button class="carousel-btn carousel-btn-prev" id="carouselPrev" aria-label="Anterior">&#8249;</button>

                <div class="carousel-viewport" id="carouselViewport">
                    <div class="carousel-track" id="carouselTrack">

                        <div class="pet-card">
                            <div class="pet-image">
                                <img src="https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=500&q=80" alt="Max">
                                <span class="pet-badge">Perro</span>
                            </div>
                            <div class="pet-info">
                                <h3>Max</h3>
                                <p class="pet-details">2 años • Mediano • Vacunado</p>
                            </div>
                        </div>

                        <div class="pet-card">
                            <div class="pet-image">
                                <img src="https://images.unsplash.com/photo-1574158622682-e40e69881006?auto=format&fit=crop&w=500&q=80" alt="Luna">
                                <span class="pet-badge badge-pink">Gato</span>
                            </div>
                            <div class="pet-info">
                                <h3>Luna</h3>
                                <p class="pet-details">4 meses • Hembra • Esterilizada</p>
                            </div>
                        </div>

                        <div class="pet-card">
                            <div class="pet-image">
                                <img src="https://images.unsplash.com/photo-1537151625747-768eb6cf92b2?auto=format&fit=crop&w=500&q=80" alt="Rocky">
                                <span class="pet-badge">Perro</span>
                            </div>
                            <div class="pet-info">
                                <h3>Rocky</h3>
                                <p class="pet-details">1 año • Grande • Vacunado</p>
                            </div>
                        </div>

                        <div class="pet-card">
                            <div class="pet-image">
                                <img src="https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?auto=format&fit=crop&w=500&q=80" alt="Milo">
                                <span class="pet-badge badge-pink">Gato</span>
                            </div>
                            <div class="pet-info">
                                <h3>Milo</h3>
                                <p class="pet-details">3 meses • Macho • Desparasitado</p>
                            </div>
                        </div>

                        <div class="pet-card">
                            <div class="pet-image">
                                <img src="https://images.unsplash.com/photo-1583511655826-05700d52f4d9?auto=format&fit=crop&w=500&q=80" alt="Benny">
                                <span class="pet-badge">Perro</span>
                            </div>
                            <div class="pet-info">
                                <h3>Benny</h3>
                                <p class="pet-details">3 años • Grande • Esterilizado</p>
                            </div>
                        </div>

                        <div class="pet-card">
                            <div class="pet-image">
                                <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=500&q=80" alt="Cleo">
                                <span class="pet-badge badge-pink">Gato</span>
                            </div>
                            <div class="pet-info">
                                <h3>Cleo</h3>
                                <p class="pet-details">1 año • Pequeña • Vacunada</p>
                            </div>
                        </div>

                        <div class="pet-card">
                            <div class="pet-image">
                                <img src="https://images.unsplash.com/photo-1587300003388-59208cc962cb?auto=format&fit=crop&w=500&q=80" alt="Duke">
                                <span class="pet-badge">Perro</span>
                            </div>
                            <div class="pet-info">
                                <h3>Duke</h3>
                                <p class="pet-details">5 años • Grande • Vacunado</p>
                            </div>
                        </div>

                    </div>
                </div>

                <button class="carousel-btn carousel-btn-next" id="carouselNext" aria-label="Siguiente">&#8250;</button>
            </div>

            <!-- Dots -->
            <div class="carousel-dots" id="carouselDots"></div>
        </div>
    </section>

    <!-- CÓMO ADOPTAR -->
    <section class="how-to-adopt" id="como-adoptar">
        <div>
            <h2>¿Cómo adoptar?</h2>

            <div class="steps-grid">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Regístrate</h3>
                    <p>Crea tu cuenta dentro de la aplicación.</p>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Explora mascotas</h3>
                    <p>Revisa el catálogo y encuentra una mascota compatible.</p>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Solicita adopción</h3>
                    <p>Envía tu solicitud al refugio correspondiente.</p>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Seguimiento</h3>
                    <p>La aplicación permite dar seguimiento post-adopción.</p>
                </div>
            </div>
        </div>
    </section>
