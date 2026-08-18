            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Mi Perfil</h1>
                    <p class="text-muted mb-0">
                        Bienvenido/a, <strong><?php echo htmlspecialchars($usuario["nombre"]); ?></strong>
                    </p>
                </div>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-label"><i class="fas fa-file-signature purple"></i> Solicitudes activas</div>
                    <div class="stat-value"><?php echo $solicitudesActivas; ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label"><i class="fas fa-heart pink"></i> Mascotas favoritas</div>
                    <div class="stat-value"><?php echo count($favoritos); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label"><i class="fas fa-calendar-check green"></i> Próximo seguimiento</div>
                    <div class="stat-value"><?php echo $proximoSeguimiento ? date('d/m/Y', strtotime($proximoSeguimiento['fecha_programada'])) : '—'; ?></div>
                </div>
            </div>

            <?php if (!empty($mascotasRecomendadas)): ?>
                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-wand-magic-sparkles me-2 text-primary"></i>Recomendadas para ti</h5>
                    </div>
                    <div class="card-body">
                        <div class="featured-pets-row">
                            <?php foreach ($mascotasRecomendadas as $m): ?>
                                <a href="detalle_mascota.php?id=<?php echo (int) $m['id_mascota']; ?>" class="featured-pet-card">
                                    <img src="<?php echo htmlspecialchars($m['foto'] ?: 'https://via.placeholder.com/400x300?text=PawsMatch'); ?>" alt="<?php echo htmlspecialchars($m['nombre']); ?>">
                                    <div class="fp-body">
                                        <strong><?php echo htmlspecialchars($m['nombre']); ?></strong>
                                        <span>
                                            <?php echo htmlspecialchars($m['raza']); ?>
                                            <?php if (isset($m['compatibilidad'])): ?>
                                                · <?php echo (int) $m['compatibilidad']; ?>% afinidad
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row g-4">

                <!-- FORMULARIO -->
                <div class="col-12 col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Información de Aptitud para Adopción</h5>
                        </div>
                        <div class="card-body">
                            <form action="php/actualizar_perfil.php" method="POST">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario["nombre"]); ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="apellidos" class="form-label">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario["apellidos"]); ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="correo" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario["correo"]); ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario["telefono"] ?? ""); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="tipo_vivienda" class="form-label">Tipo de Vivienda</label>
                                        <select class="form-select" id="tipo_vivienda" name="tipo_vivienda">
                                            <option value="">Seleccionar</option>
                                            <option value="CASA" <?php echo ($usuario["tipo_vivienda"] === "CASA") ? "selected" : ""; ?>>Casa</option>
                                            <option value="APARTAMENTO" <?php echo ($usuario["tipo_vivienda"] === "APARTAMENTO") ? "selected" : ""; ?>>Apartamento</option>
                                            <option value="OTRO" <?php echo ($usuario["tipo_vivienda"] === "OTRO") ? "selected" : ""; ?>>Otro</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="tiempo_disponible" class="form-label">Disponibilidad de Tiempo</label>
                                        <select class="form-select" id="tiempo_disponible" name="tiempo_disponible">
                                            <option value="">Seleccionar</option>
                                            <option value="BAJO" <?php echo ($usuario["tiempo_disponible"] === "BAJO") ? "selected" : ""; ?>>Baja</option>
                                            <option value="MEDIO" <?php echo ($usuario["tiempo_disponible"] === "MEDIO") ? "selected" : ""; ?>>Media</option>
                                            <option value="ALTO" <?php echo ($usuario["tiempo_disponible"] === "ALTO") ? "selected" : ""; ?>>Alta</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="experiencia_mascotas" class="form-label">Experiencia con Mascotas</label>
                                        <select class="form-select" id="experiencia_mascotas" name="experiencia_mascotas">
                                            <option value="">Seleccionar</option>
                                            <?php foreach (["NINGUNA" => "Ninguna", "BASICA" => "Básica", "INTERMEDIA" => "Intermedia", "ALTA" => "Alta"] as $valor => $texto): ?>
                                                <option value="<?php echo $valor; ?>" <?php echo ($usuario["experiencia_mascotas"] === $valor) ? "selected" : ""; ?>><?php echo $texto; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="preferencia_especie" class="form-label">Especie Preferida</label>
                                        <select class="form-select" id="preferencia_especie" name="preferencia_especie">
                                            <option value="">Sin preferencia</option>
                                            <option value="Perro" <?php echo ($usuario["preferencia_especie"] === "Perro") ? "selected" : ""; ?>>Perro</option>
                                            <option value="Gato" <?php echo ($usuario["preferencia_especie"] === "Gato") ? "selected" : ""; ?>>Gato</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="preferencia_tamano" class="form-label">Tamaño Preferido</label>
                                        <select class="form-select" id="preferencia_tamano" name="preferencia_tamano">
                                            <option value="">Sin preferencia</option>
                                            <option value="PEQUENO" <?php echo ($usuario["preferencia_tamano"] === "PEQUENO") ? "selected" : ""; ?>>Pequeño</option>
                                            <option value="MEDIANO" <?php echo ($usuario["preferencia_tamano"] === "MEDIANO") ? "selected" : ""; ?>>Mediano</option>
                                            <option value="GRANDE" <?php echo ($usuario["preferencia_tamano"] === "GRANDE") ? "selected" : ""; ?>>Grande</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Condiciones del Hogar</label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tiene_patio" name="tiene_patio" value="1" <?php echo !empty($usuario["tiene_patio"]) ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="tiene_patio">Tengo patio</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tiene_otros_animales" name="tiene_otros_animales" value="1" <?php echo !empty($usuario["tiene_otros_animales"]) ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="tiene_otros_animales">Tengo otros animales</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tiene_ninos" name="tiene_ninos" value="1" <?php echo !empty($usuario["tiene_ninos"]) ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="tiene_ninos">Hay niños en el hogar</label>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Actualizar Perfil y Recalcular Matching
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA -->
                <div class="col-12 col-lg-4">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Mi Cuenta</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="rounded-circle mx-auto mb-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width:90px;height:90px;font-size:2rem;">
                                <i class="fas fa-user"></i>
                            </div>
                            <h5><?php echo htmlspecialchars($nombreCompleto); ?></h5>
                            <p class="text-muted"><?php echo htmlspecialchars($usuario["correo"]); ?></p>
                            <span class="badge bg-success">Adoptante</span>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">Mi Firma Digital</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="border rounded p-4 mb-3 bg-light" style="min-height:130px;display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:1.7rem;font-style:italic;"><?php echo htmlspecialchars($nombreCompleto); ?></span>
                            </div>
                            <p class="small text-muted">Esta firma podrá utilizarse posteriormente en el proceso de adopción.</p>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0"><i class="fas fa-heart me-2"></i>Mis Favoritos</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($favoritos)): ?>
                                <p class="text-muted small mb-0">
                                    Aún no tienes mascotas favoritas. Explora el <a href="catalogo.php">catálogo</a> y guarda las que más te gusten.
                                </p>
                            <?php else: ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($favoritos as $fav): ?>
                                        <li class="list-group-item d-flex align-items-center gap-2 px-0">
                                            <img src="<?php echo htmlspecialchars($fav['foto'] ?: 'https://via.placeholder.com/40'); ?>" width="40" height="40" style="border-radius:10px;object-fit:cover" alt="<?php echo htmlspecialchars($fav['nombre']); ?>">
                                            <div class="flex-grow-1">
                                                <a href="detalle_mascota.php?id=<?php echo (int) $fav['id_mascota']; ?>" class="text-decoration-none">
                                                    <strong><?php echo htmlspecialchars($fav['nombre']); ?></strong>
                                                </a>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($fav['especie']); ?></small>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>
