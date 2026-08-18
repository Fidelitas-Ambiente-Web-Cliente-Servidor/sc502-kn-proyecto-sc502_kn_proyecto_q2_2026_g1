            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2">Mensajes</h1>
                    <p class="text-muted mb-0">
                        Sobre <strong><?php echo htmlspecialchars($info['mascota']); ?></strong>
                        — <?php echo htmlspecialchars($otroParticipante); ?>
                    </p>
                </div>
                <a href="<?php echo htmlspecialchars($volverA); ?>" class="btn btn-outline-secondary btn-sm">← Volver a solicitudes</a>
            </div>

            <div class="card">
                <div class="card-body" style="max-height:60vh;overflow-y:auto;display:flex;flex-direction:column;gap:.8rem;">

                    <?php if (empty($mensajes)): ?>
                        <p class="text-muted text-center py-4 mb-0">Todavía no hay mensajes. Escribe el primero.</p>
                    <?php else: ?>
                        <?php foreach ($mensajes as $m): ?>
                            <?php $esMio = (int) $m['id_remitente'] === (int) $_SESSION['id_usuario']; ?>
                            <div style="align-self:<?php echo $esMio ? 'flex-end' : 'flex-start'; ?>;max-width:75%;">
                                <div style="background:<?php echo $esMio ? '#6c5ce7' : '#f3f4f6'; ?>;color:<?php echo $esMio ? '#fff' : '#1f2328'; ?>;padding:.6rem .9rem;border-radius:14px;<?php echo $esMio ? 'border-bottom-right-radius:4px;' : 'border-bottom-left-radius:4px;'; ?>">
                                    <?php if (!$esMio): ?>
                                        <div style="font-size:.72rem;font-weight:700;opacity:.7;margin-bottom:.15rem;"><?php echo htmlspecialchars($m['remitente_nombre']); ?></div>
                                    <?php endif; ?>
                                    <div style="font-size:.9rem;white-space:pre-wrap;"><?php echo htmlspecialchars($m['mensaje']); ?></div>
                                </div>
                                <div style="font-size:.72rem;color:#9ca3af;margin-top:.2rem;text-align:<?php echo $esMio ? 'right' : 'left'; ?>;">
                                    <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($m['fecha_envio']))); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

                <div class="card-footer bg-white">
                    <form action="php/enviar_mensaje.php" method="POST" class="d-flex gap-2">
                        <input type="hidden" name="id_solicitud" value="<?php echo (int) $info['id_solicitud']; ?>">
                        <input type="text" name="mensaje" class="form-control" placeholder="Escribe un mensaje..." required autofocus>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
