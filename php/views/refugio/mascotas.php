            <div class="admin-header">
                <div>
                    <h1>Gestión de mascotas</h1>
                    <p>Administre, publique y edite las mascotas de su refugio.</p>
                </div>
                <div class="admin-actions">
                    <?php if ($refugio['estado'] === 'APROBADO'): ?>
                        <button class="btn btn-primary" data-open-modal="mascotaModal">Nueva mascota</button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($refugio['estado'] !== 'APROBADO'): ?>
                <div class="admin-card" style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;margin-bottom:1.2rem;padding:1rem 1.2rem;">
                    Tu refugio todavía no está aprobado, así que no puedes registrar mascotas por ahora.
                </div>
            <?php endif; ?>

            <div class="kpi-grid">
                <div class="kpi-card"><div><p>Total mascotas</p><h3><?php echo $total; ?></h3></div></div>
                <div class="kpi-card"><div><p>Disponibles</p><h3><?php echo $disponibles; ?></h3></div></div>
                <div class="kpi-card"><div><p>Inactivas</p><h3><?php echo $inactivas; ?></h3></div></div>
                <div class="kpi-card"><div><p>Adoptadas</p><h3><?php echo $adoptadas; ?></h3></div></div>
            </div>

            <section class="admin-card">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Mascota</th><th>Especie</th><th>Edad</th><th>Sexo</th><th>Estado</th><th class="text-end">Acciones</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($mascotas)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">Todavía no has registrado mascotas. Usa "Nueva mascota" para comenzar.</td></tr>
                            <?php else: ?>
                                <?php foreach ($mascotas as $m): ?>
                                    <tr>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:.8rem">
                                                <img src="<?php echo htmlspecialchars($m['foto'] ?: 'https://via.placeholder.com/48'); ?>" width="48" height="48" style="border-radius:12px;object-fit:cover;flex-shrink:0" alt="">
                                                <div>
                                                    <strong><?php echo htmlspecialchars($m['nombre']); ?></strong><br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($m['raza'] ?: $m['especie']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($m['especie']); ?></td>
                                        <td><?php echo $m['edad'] !== null ? (int) $m['edad'] . ' años' : '—'; ?></td>
                                        <td><?php echo $m['sexo'] === 'MACHO' ? 'Macho' : ($m['sexo'] === 'HEMBRA' ? 'Hembra' : '—'); ?></td>
                                        <td><span class="badge" style="<?php echo $estadoBadge[$m['estado']] ?? ''; ?>padding:.3rem .7rem;border-radius:99px;font-size:.78rem;"><?php echo $estadoTexto[$m['estado']] ?? $m['estado']; ?></span></td>
                                        <td class="text-end" style="white-space:nowrap">

                                            <button type="button" class="btn btn-sm btn-outline me-1" title="Editar"
                                                onclick='abrirEdicion(<?php echo json_encode($m, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>Editar</button>

                                            <?php if (in_array($m['estado'], ['DISPONIBLE', 'INACTIVO'])): ?>
                                                <form action="php/refugio_mascota_estado.php" method="POST" style="display:inline">
                                                    <input type="hidden" name="id_mascota" value="<?php echo (int) $m['id_mascota']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline me-1" title="<?php echo $m['estado'] === 'DISPONIBLE' ? 'Ocultar' : 'Publicar'; ?>">
                                                        <?php echo $m['estado'] === 'DISPONIBLE' ? 'Ocultar' : 'Publicar'; ?>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if ($m['estado'] !== 'ADOPTADO'): ?>
                                                <form action="php/refugio_mascota_eliminar.php" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar esta mascota? Esta acción no se puede deshacer.');">
                                                    <input type="hidden" name="id_mascota" value="<?php echo (int) $m['id_mascota']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger-outline" title="Eliminar">Eliminar</button>
                                                </form>
                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ADD / EDIT MASCOTA MODAL -->
            <div class="modal" id="mascotaModal">
                <div class="modal-dialog" style="max-width:680px">
                    <form id="formMascota" action="php/refugio_mascota_guardar.php" method="POST">
                        <input type="hidden" id="mascotaId" name="id_mascota">
                        <div class="modal-header">
                            <h2 id="mascotaModalTitle">Nueva mascota</h2>
                            <button type="button" class="close-modal" data-close-modal>&times;</button>
                        </div>
                        <div class="modal-body form-grid">
                            <div>
                                <label class="form-label">Nombre *</label>
                                <input id="mNombre" name="nombre" class="form-control" required placeholder="Nombre de la mascota">
                            </div>
                            <div>
                                <label class="form-label">Especie *</label>
                                <select id="mEspecie" name="especie" class="form-select" required>
                                    <option value="">Selecciona</option>
                                    <option>Perro</option>
                                    <option>Gato</option>
                                    <option>Otro</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Raza</label>
                                <input id="mRaza" name="raza" class="form-control" placeholder="Labrador, Mestizo…">
                            </div>
                            <div>
                                <label class="form-label">Edad (años)</label>
                                <input id="mEdad" name="edad" type="number" min="0" max="30" class="form-control">
                            </div>
                            <div>
                                <label class="form-label">Sexo</label>
                                <select id="mSexo" name="sexo" class="form-select">
                                    <option value="">Selecciona</option>
                                    <option value="MACHO">Macho</option>
                                    <option value="HEMBRA">Hembra</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Tamaño</label>
                                <select id="mTamano" name="tamano" class="form-select">
                                    <option value="">Selecciona</option>
                                    <option value="PEQUENO">Pequeño</option>
                                    <option value="MEDIANO">Mediano</option>
                                    <option value="GRANDE">Grande</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Nivel de energía</label>
                                <select id="mEnergia" name="nivel_energia" class="form-select">
                                    <option value="">Selecciona</option>
                                    <option value="BAJO">Bajo</option>
                                    <option value="MEDIO">Medio</option>
                                    <option value="ALTO">Alto</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">URL de foto</label>
                                <input id="mFoto" name="foto" class="form-control" placeholder="https://…">
                            </div>
                            <div style="grid-column:1/-1">
                                <label class="form-label">Descripción</label>
                                <textarea id="mDescripcion" name="descripcion" class="form-control" rows="3" placeholder="Descripción de la mascota…"></textarea>
                            </div>
                            <div style="grid-column:1/-1;display:flex;gap:1.4rem;flex-wrap:wrap">
                                <label style="display:flex;align-items:center;gap:.4rem"><input type="checkbox" id="mVacunado" name="vacunado" value="1"> Vacunado</label>
                                <label style="display:flex;align-items:center;gap:.4rem"><input type="checkbox" id="mEsterilizado" name="esterilizado" value="1"> Esterilizado</label>
                                <label style="display:flex;align-items:center;gap:.4rem"><input type="checkbox" id="mNinos" name="compatible_ninos" value="1"> Compatible con niños</label>
                                <label style="display:flex;align-items:center;gap:.4rem"><input type="checkbox" id="mAnimales" name="compatible_animales" value="1"> Compatible con otros animales</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-close-modal>Cancelar</button>
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
