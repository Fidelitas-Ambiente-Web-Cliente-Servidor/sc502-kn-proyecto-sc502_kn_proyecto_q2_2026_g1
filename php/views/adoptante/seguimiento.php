            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Seguimiento de Bienestar</h1>
            </div>

            <?php if (empty($adopciones)): ?>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aún no tienes adopciones completadas. Cuando un refugio apruebe una de tus solicitudes,
                    aquí podrás dar seguimiento al bienestar de tu mascota.
                </div>

            <?php else: ?>

                <?php foreach ($adopciones as $adopcion): ?>

                    <div class="card mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <?php echo htmlspecialchars($adopcion['mascota']); ?>
                                <small class="text-muted">— adoptado el <?php echo htmlspecialchars(date('d/m/Y', strtotime($adopcion['fecha_adopcion']))); ?></small>
                            </h5>
                            <span class="badge <?php echo $adopcion['estado_adopcion'] === 'ACTIVA' ? 'bg-success' : ($adopcion['estado_adopcion'] === 'DEVUELTA' ? 'bg-danger' : 'bg-secondary'); ?>">
                                <?php echo $adopcion['estado_adopcion']; ?>
                            </span>
                        </div>
                        <div class="card-body">

                            <?php $segs = $seguimientosPorAdopcion[$adopcion['id_adopcion']] ?? []; ?>

                            <?php if (empty($segs)): ?>
                                <p class="text-muted mb-0">El refugio todavía no ha programado seguimientos para esta adopción.</p>
                            <?php else: ?>
                                <ul class="timeline">
                                    <?php foreach ($segs as $seg): ?>
                                        <li class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="card border p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="mb-0"><?php echo $tiposSeguimiento[$seg['tipo']] ?? $seg['tipo']; ?></h6>
                                                    <span class="badge <?php echo $seg['estado'] === 'COMPLETADO' ? 'bg-success' : ($seg['estado'] === 'VENCIDO' ? 'bg-danger' : 'bg-warning text-dark'); ?>">
                                                        <?php echo $seg['estado']; ?>
                                                    </span>
                                                </div>
                                                <p class="small text-muted mb-2">Programado: <?php echo htmlspecialchars(date('d/m/Y', strtotime($seg['fecha_programada']))); ?></p>

                                                <?php if ($seg['estado'] === 'COMPLETADO'): ?>
                                                    <div class="row g-2 small text-muted mb-2">
                                                        <div class="col-6"><i class="fas fa-notes-medical me-1"></i> Salud: <?php echo htmlspecialchars($seg['estado_salud'] ?: 'N/D'); ?></div>
                                                    </div>
                                                    <p class="mb-0"><?php echo htmlspecialchars($seg['adaptacion'] ?: ''); ?></p>
                                                <?php else: ?>
                                                    <form action="php/completar_seguimiento.php" method="POST" class="mt-2">
                                                        <input type="hidden" name="id_seguimiento" value="<?php echo (int) $seg['id_seguimiento']; ?>">
                                                        <div class="row g-2">
                                                            <div class="col-md-6">
                                                                <input type="text" name="estado_salud" class="form-control form-control-sm" placeholder="Estado de salud (ej. Óptima)">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" name="adaptacion" class="form-control form-control-sm" placeholder="¿Cómo se ha adaptado?">
                                                            </div>
                                                            <div class="col-12">
                                                                <textarea name="observaciones" class="form-control form-control-sm" rows="2" placeholder="Observaciones adicionales"></textarea>
                                                            </div>
                                                            <div class="col-12 text-end">
                                                                <button type="submit" class="btn btn-sm btn-success mt-1">
                                                                    <i class="fas fa-paper-plane me-1"></i> Enviar reporte
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if ($adopcion['estado_adopcion'] === 'ACTIVA'): ?>
                                <hr>
                                <form action="php/solicitar_devolucion.php" method="POST" onsubmit="return confirm('¿Iniciar el proceso de devolución de esta mascota? Volverá a estar disponible para adopción.');">
                                    <input type="hidden" name="id_adopcion" value="<?php echo (int) $adopcion['id_adopcion']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-undo me-1"></i> Iniciar proceso de devolución
                                    </button>
                                </form>
                            <?php endif; ?>

                        </div>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>
