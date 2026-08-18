    <section class="register-shell" style="grid-template-columns:1fr;max-width:520px;">

        <div class="register-form-area">

            <div class="register-form-header">
                <h1>Crear nueva contraseña</h1>
                <p>Escribe tu nueva contraseña para completar la recuperación.</p>
            </div>

            <?php if (!$tokenValido): ?>

                <div style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;border-radius:12px;padding:1rem 1.2rem;margin-bottom:1rem;font-size:.9rem;">
                    Este enlace no es válido o ya expiró. Solicita uno nuevo.
                </div>

                <a href="recuperar_contraseña.html" class="btn btn-primary auth-btn" style="display:block;text-align:center;text-decoration:none;">
                    Solicitar nuevo enlace
                </a>

            <?php else: ?>

                <form action="php/restablecer_password.php" method="POST" class="register-form">

                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div class="form-group">
                        <label for="contrasena">Nueva contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" minlength="6" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmar_contrasena">Confirmar contraseña</label>
                        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Confirmar contraseña" minlength="6" required>
                    </div>

                    <button type="submit" class="btn btn-primary auth-btn">
                        Restablecer contraseña
                    </button>

                </form>

            <?php endif; ?>

            <div class="back-home">
                <a href="login.html">← Volver a iniciar sesión</a>
            </div>

        </div>

    </section>
