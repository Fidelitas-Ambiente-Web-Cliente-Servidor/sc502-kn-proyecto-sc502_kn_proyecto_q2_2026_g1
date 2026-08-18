    <section class="detail-container">

        <div class="detail-image">

            <img
                src="<?php echo htmlspecialchars($mascota['foto'] ?: 'https://via.placeholder.com/600x500?text=PawsMatch'); ?>"
                alt="<?php echo htmlspecialchars($mascota['nombre']); ?>"
            >

        </div>

        <div class="detail-info">

            <span class="pet-tag">
                <?php echo htmlspecialchars($estados[$mascota['estado']] ?? $mascota['estado']); ?>
            </span>

            <?php if ($compatibilidad !== null): ?>
                <?php
                    $colorTexto = $compatibilidad >= 70 ? '#166534' : ($compatibilidad >= 40 ? '#92400e' : '#4b5563');
                    $colorFondo = $compatibilidad >= 70 ? '#dcfce7' : ($compatibilidad >= 40 ? '#fef3c7' : '#f3f4f6');
                ?>
                <span class="pet-tag" style="background:<?php echo $colorFondo; ?>;color:<?php echo $colorTexto; ?>;margin-left:.5rem;">
                    <?php echo $compatibilidad; ?>% compatible contigo
                </span>
            <?php endif; ?>

            <h1><?php echo htmlspecialchars($mascota['nombre']); ?></h1>

            <h3><?php echo htmlspecialchars($mascota['raza'] ?: $mascota['especie']); ?></h3>

            <p class="detail-description">
                <?php echo nl2br(htmlspecialchars($mascota['descripcion'] ?: 'Esta mascota todavía no tiene una descripción registrada.')); ?>
            </p>

            <div class="detail-data">

                <div class="data-card">
                    <h4>Edad</h4>
                    <span>
                        <?php if ($mascota['edad'] !== null): ?>
                            <?php echo (int) $mascota['edad']; ?> <?php echo ((int) $mascota['edad'] === 1) ? 'año' : 'años'; ?>
                        <?php else: ?>
                            No especificada
                        <?php endif; ?>
                    </span>
                </div>

                <div class="data-card">
                    <h4>Sexo</h4>
                    <span><?php echo htmlspecialchars($sexos[$mascota['sexo']] ?? 'No especificado'); ?></span>
                </div>

                <div class="data-card">
                    <h4>Tamaño</h4>
                    <span><?php echo htmlspecialchars($tamanos[$mascota['tamano']] ?? 'No especificado'); ?></span>
                </div>

                <div class="data-card">
                    <h4>Vacunas</h4>
                    <span><?php echo $mascota['vacunado'] ? 'Al día' : 'Pendientes'; ?></span>
                </div>

            </div>

            <?php if (!empty($rasgos)): ?>
                <div class="detail-features">
                    <?php foreach ($rasgos as $rasgo): ?>
                        <span><?php echo htmlspecialchars($rasgo); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <p class="detail-description" style="margin-top:1rem;">
                <strong>Refugio:</strong> <?php echo htmlspecialchars($mascota['nombre_refugio']); ?>
                <?php if (!empty($mascota['refugio_direccion'])): ?>
                    — <?php echo htmlspecialchars($mascota['refugio_direccion']); ?>
                <?php endif; ?>
            </p>

            <div class="detail-actions" style="flex-wrap:wrap;">

                <?php if (!isset($_SESSION["id_usuario"])): ?>

                    <a href="login.html" class="adopt-btn">Iniciar sesión para adoptar</a>

                <?php elseif ($esAdoptante && $mascota['estado'] !== 'DISPONIBLE' && !$solicitudPropia): ?>

                    <span class="pet-tag" style="background:#f3f4f6;color:#6b7280;">Esta mascota ya no está disponible</span>

                <?php elseif ($esAdoptante && $solicitudPropia): ?>

                    <span class="pet-tag"><?php echo htmlspecialchars($estadosSolicitud[$solicitudPropia] ?? $solicitudPropia); ?></span>

                <?php elseif ($esAdoptante): ?>

                    <form action="php/solicitar_adopcion.php" method="POST" style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center;">
                        <input type="hidden" name="id_mascota" value="<?php echo (int) $mascota['id_mascota']; ?>">
                        <button type="submit" class="adopt-btn" style="border:none;cursor:pointer;">
                            Solicitar adopción
                        </button>
                    </form>

                <?php endif; ?>

                <?php if ($esAdoptante): ?>
                    <form action="php/favorito_toggle.php" method="POST">
                        <input type="hidden" name="id_mascota" value="<?php echo (int) $mascota['id_mascota']; ?>">
                        <input type="hidden" name="volver_a" value="detalle_mascota.php?id=<?php echo (int) $mascota['id_mascota']; ?>">
                        <button type="submit" class="secondary-btn" style="cursor:pointer;">
                            <?php echo $esFavorito ? 'En favoritos' : 'Agregar a favoritos'; ?>
                        </button>
                    </form>
                <?php endif; ?>

                <a href="catalogo.php" class="secondary-btn">
                    Volver al catálogo
                </a>

                <?php if (isset($_SESSION["id_usuario"])): ?>
                    <button type="button" class="secondary-btn" style="cursor:pointer;border:1px solid #ef4444;color:#ef4444;" onclick="document.getElementById('modalReporte').style.display='flex'">
                        Reportar esta mascota
                    </button>
                <?php endif; ?>

            </div>

        </div>

    </section>

    <?php if (isset($_SESSION["id_usuario"])): ?>
        <div id="modalReporte" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:1000;align-items:center;justify-content:center;">
            <div style="background:#fff;border-radius:16px;padding:1.6rem;max-width:420px;width:90%;">
                <h3 style="margin-bottom:.4rem;">Reportar a <?php echo htmlspecialchars($mascota['nombre']); ?></h3>
                <p style="color:#6b7280;font-size:.85rem;margin-bottom:1rem;">
                    Usa este formulario para reportar maltrato o una preocupación de bienestar. Un administrador lo revisará.
                </p>
                <form action="php/reportar_mascota.php" method="POST">
                    <input type="hidden" name="id_mascota" value="<?php echo (int) $mascota['id_mascota']; ?>">

                    <label class="form-label" style="display:block;font-size:.85rem;margin-bottom:.3rem;">Tipo de denuncia</label>
                    <select name="tipo" required style="width:100%;padding:.6rem;border-radius:8px;border:1px solid #d1d5db;margin-bottom:.8rem;">
                        <option value="MALTRATO">Maltrato</option>
                        <option value="BIENESTAR">Bienestar</option>
                        <option value="OTRO">Otro</option>
                    </select>

                    <label class="form-label" style="display:block;font-size:.85rem;margin-bottom:.3rem;">Descripción</label>
                    <textarea name="descripcion" rows="4" required placeholder="Describe la situación con el mayor detalle posible..." style="width:100%;padding:.6rem;border-radius:8px;border:1px solid #d1d5db;margin-bottom:1rem;"></textarea>

                    <div style="display:flex;gap:.6rem;justify-content:flex-end;">
                        <button type="button" style="background:#f3f4f6;border:none;padding:.6rem 1.2rem;border-radius:8px;cursor:pointer;" onclick="document.getElementById('modalReporte').style.display='none'">Cancelar</button>
                        <button type="submit" style="background:#ef4444;color:#fff;border:none;padding:.6rem 1.2rem;border-radius:8px;cursor:pointer;">Enviar denuncia</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
