    <!-- HERO -->
    <section class="info-page-hero">
        <div class="info-page-hero-content">
            <span class="pet-tag">Estamos aquí para ayudarte</span>
            <h1>Contacta con PawsMatch</h1>
            <p>¿Tienes dudas sobre el proceso de adopción, quieres registrar tu refugio o simplemente necesitas orientación? Escríbenos, estamos para ayudarte.</p>
        </div>
        <div class="info-page-hero-img">
            <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=800&q=80" alt="Perros felices">
        </div>
    </section>

    <!-- CONTACTO PRINCIPAL -->
    <section class="contact-section">
        <div class="contact-inner">

            <!-- FORMULARIO -->
            <div class="contact-form-card">
                <h2>Envíanos un mensaje</h2>
                <p>Respondemos en menos de 24 horas hábiles.</p>
                <form class="contact-form" id="contactForm">
                    <div class="form-group">
                        <label for="contactNombre">Nombre completo</label>
                        <input type="text" id="contactNombre" placeholder="Tu nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="contactCorreo">Correo electrónico</label>
                        <input type="email" id="contactCorreo" placeholder="correo@ejemplo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="contactAsunto">Asunto</label>
                        <select id="contactAsunto">
                            <option value="">Selecciona un asunto</option>
                            <option>Proceso de adopción</option>
                            <option>Registrar un refugio</option>
                            <option>Reportar un problema</option>
                            <option>Información general</option>
                            <option>Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contactMensaje">Mensaje</label>
                        <textarea id="contactMensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
                    </div>
                    <button type="submit" class="adopt-btn contact-submit">Enviar mensaje</button>
                </form>
                <div id="contactSuccess" class="contact-success" style="display:none;">
                    ¡Mensaje enviado! Te responderemos pronto.
                </div>
            </div>

            <!-- INFO LATERAL -->
            <div class="contact-info-col">
                <div class="contact-info-card">
                    <h3>Teléfono / WhatsApp</h3>
                    <p>+506 6000-0000</p>
                    <small>Lun - Vie: 8:00 am – 5:00 pm</small>
                </div>
                <div class="contact-info-card">
                    <h3>Correo electrónico</h3>
                    <p>hola@pawsmatch.com</p>
                    <small>Respondemos en menos de 24 h</small>
                </div>
                <div class="contact-info-card">
                    <h3>Ubicación</h3>
                    <p>San José, Costa Rica</p>
                    <small>Atención solo virtual por el momento</small>
                </div>
                <div class="contact-info-card">
                    <h3>¿Eres un refugio?</h3>
                    <p>Regístralo en la plataforma y empieza a publicar mascotas para adopción.</p>
                    <a href="registro-refugio.html" class="pet-btn" style="margin-top:0.6rem;">Registrar mi refugio</a>
                </div>
            </div>

        </div>
    </section>
