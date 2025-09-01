<?php
$sesiones = $data['sesiones'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
$max_sesiones = $data['max_sesiones'] ?? 1;
$sesiones_activas = count($sesiones);
?>
<div class="card">
    <div class="card-header">
        <h3>Gestión de Sesiones</h3>
    </div>
    <div class="card-body">
        <div class="session-stats">
            <p><strong>Sesiones activas:</strong> <?php echo $sesiones_activas; ?> de <?php echo $max_sesiones; ?> permitidas</p>
            <?php if ($sesiones_activas >= $max_sesiones): ?>
                <div class="session-limit-warning">
                    Has alcanzado el límite de sesiones concurrentes. Cierra algunas sesiones para poder iniciar en nuevos dispositivos.
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (empty($sesiones)): ?>
            <div style="text-align: center; padding: 20px;">
                <p style="color: var(--text-light);">No hay sesiones activas</p>
            </div>
        <?php else: ?>
            <div class="sessions-list">
                <?php foreach ($sesiones as $sesion): ?>
                <div class="session-item <?php echo $sesion['session_id'] === session_id() ? 'session-current' : ''; ?>">
                    <div class="session-info">
                        <strong>IP: <?php echo htmlspecialchars($sesion['ip_address']); ?></strong>
                        
                        <div class="session-device-info">
                            <span><?php echo htmlspecialchars($sesion['user_agent_parsed']); ?></span>
                        </div>
                        
                        <span>Última actividad: <?php echo date('d/m/Y H:i', strtotime($sesion['fecha_ultima_actividad'])); ?></span>
                        
                        <?php if ($sesion['session_id'] === session_id()): ?>
                            <span class="session-status session-status-active">Sesión actual</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="session-actions">
                        <?php if ($sesion['session_id'] !== session_id()): ?>
                        <button onclick="cerrarSesion('<?php echo $sesion['session_id']; ?>')" 
                                class="btn btn-sm btn-danger"
                                title="Cerrar esta sesión">
                            Cerrar sesión
                        </button>
                        <?php else: ?>
                        <span class="btn btn-sm btn-secondary" style="opacity: 0.7;">Sesión actual</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
            <p style="color: var(--text-light); font-size: 14px; margin: 0;">
                <strong>Nota:</strong> Las sesiones se cierran automáticamente después de 24 horas de inactividad.
            </p>
        </div>
    </div>
</div>

<script>
function cerrarSesion(session_id) {
    if (confirm('¿Estás seguro de que quieres cerrar esta sesión?\n\nEl usuario deberá iniciar sesión nuevamente en ese dispositivo.')) {
        const button = event.target;
        const originalText = button.textContent;
        
        button.classList.add('session-btn-loading');
        button.textContent = 'Procesando...';
        button.disabled = true;
        
        fetch('<?php echo url('profile/cerrar-sesion'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ session_id: session_id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Sesión cerrada exitosamente');
                window.location.reload();
            } else {
                alert('Error al cerrar la sesión: ' + (data.message || 'Error desconocido'));
                button.classList.remove('session-btn-loading');
                button.textContent = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            alert('Error de conexión. Intenta nuevamente.');
            button.classList.remove('session-btn-loading');
            button.textContent = originalText;
            button.disabled = false;
        });
    }
}
</script>