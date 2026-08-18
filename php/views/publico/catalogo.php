    <section class="catalog-top">

        <h1>Catálogo de Mascotas</h1>

        <p>
            Explora las mascotas disponibles y encuentra la compañera perfecta para tu hogar.
        </p>

        <div class="catalog-search">
            <input type="text" placeholder="Buscar por nombre o raza...">

            <button>
                Buscar
            </button>
        </div>

        <div class="catalog-filters">
            <button class="active">Todos</button>
            <button>Perros</button>
            <button>Gatos</button>
            <button>Cachorros</button>
        </div>

    </section>

    <?php if ($esAdoptanteSinPreferencias): ?>
        <div style="max-width:900px;margin:0 auto 1.5rem;background:#eef2ff;border:1px solid #c7d2fe;color:#3730a3;border-radius:12px;padding:.9rem 1.2rem;font-size:.9rem;">
            Completa tus preferencias en <a href="perfil.php" style="font-weight:600;">tu perfil</a> y te mostraremos qué tan compatible es cada mascota contigo.
        </div>
    <?php elseif ($perfilAdoptante): ?>
        <div style="max-width:900px;margin:0 auto 1.5rem;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:12px;padding:.9rem 1.2rem;font-size:.9rem;">
            Ordenamos estas mascotas según tu compatibilidad con cada una, según las preferencias de tu perfil.
        </div>
    <?php endif; ?>

    <section class="pets-grid">

        <?php if (empty($mascotas)): ?>

            <p style="grid-column:1/-1;text-align:center;color:#6b7280;padding:2rem;">
                Todavía no hay mascotas disponibles para adopción.
            </p>

        <?php else: ?>

            <?php foreach ($mascotas as $m): ?>

                <article class="pet-card" style="position:relative;">

                    <?php if (isset($m['compatibilidad']) && $m['compatibilidad'] !== null): ?>
                        <?php
                            $pct = $m['compatibilidad'];
                            $colorFondo = $pct >= 70 ? '#166534' : ($pct >= 40 ? '#92400e' : '#4b5563');
                            $colorFondoClaro = $pct >= 70 ? '#dcfce7' : ($pct >= 40 ? '#fef3c7' : '#f3f4f6');
                        ?>
                        <span style="position:absolute;top:.6rem;right:.6rem;z-index:2;background:<?php echo $colorFondoClaro; ?>;color:<?php echo $colorFondo; ?>;font-weight:700;font-size:.78rem;padding:.3rem .7rem;border-radius:99px;">
                            <?php echo $pct; ?>% compatible
                        </span>
                    <?php endif; ?>

                    <img
                        src="<?php echo htmlspecialchars($m['foto'] ?: 'https://via.placeholder.com/500x400?text=PawsMatch'); ?>"
                        alt="<?php echo htmlspecialchars($m['nombre']); ?>"
                    >

                    <div class="pet-info">
                        <h3><?php echo htmlspecialchars($m['nombre']); ?></h3>
                        <p>
                            <?php echo htmlspecialchars($m['raza'] ?: $m['especie']); ?>
                            <?php if ($m['edad'] !== null): ?>
                                • <?php echo (int) $m['edad']; ?> <?php echo ((int) $m['edad'] === 1) ? 'año' : 'años'; ?>
                            <?php endif; ?>
                        </p>

                        <a href="detalle_mascota.php?id=<?php echo (int) $m['id_mascota']; ?>" class="pet-btn">
                            Ver detalles
                        </a>
                    </div>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </section>
