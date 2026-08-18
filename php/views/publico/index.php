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
                    <h3><?php echo $statAdopciones; ?></h3>
                    <p>Adopciones exitosas</p>
                </div>
            </div>

            <div class="impact-item">
                <div>
                    <h3><?php echo $statRefugios; ?></h3>
                    <p>Refugios aliados</p>
                </div>
            </div>

            <div class="impact-item">
                <div>
                    <h3><?php echo $statFamilias; ?></h3>
                    <p>Familias felices</p>
                </div>
            </div>

            <div class="impact-item">
                <div>
                    <h3><?php echo $statDisponibles; ?></h3>
                    <p>Mascotas disponibles</p>
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

                        <?php
                        $tamanosCarousel = ["PEQUENO" => "Pequeño", "MEDIANO" => "Mediano", "GRANDE" => "Grande"];
                        ?>
                        <?php foreach ($mascotasCarousel as $m): ?>
                            <div class="pet-card">
                                <div class="pet-image">
                                    <img src="<?php echo htmlspecialchars($m['foto'] ?: 'https://via.placeholder.com/500x400?text=PawsMatch'); ?>" alt="<?php echo htmlspecialchars($m['nombre']); ?>">
                                    <span class="pet-badge<?php echo $m['especie'] === 'Gato' ? ' badge-pink' : ''; ?>"><?php echo htmlspecialchars($m['especie']); ?></span>
                                </div>
                                <div class="pet-info">
                                    <h3><?php echo htmlspecialchars($m['nombre']); ?></h3>
                                    <p class="pet-details">
                                        <?php echo $m['edad'] > 0 ? ((int) $m['edad']) . (((int) $m['edad'] === 1) ? ' año' : ' años') : 'Menor a 1 año'; ?>
                                        • <?php echo $tamanosCarousel[$m['tamano']] ?? $m['tamano']; ?>
                                        • <?php echo $m['vacunado'] ? 'Vacunado/a' : 'Vacunación pendiente'; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>

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
