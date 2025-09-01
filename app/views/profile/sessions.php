<?php
$sesiones = $data['sesiones'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
$max_sesiones = $data['max_sesiones'] ?? 1;
$sesiones_activas = count($sesiones);
?>
<div class="profile-card">
    <h2>Gestión de Sesiones</h2>
    
    <div class="session-stats">
        <p>Sesiones activas: <strong><?php echo $sesiones_activas; ?></strong> de <strong><?php echo $max_sesiones; ?></strong> permitidas</p>
    </div>
    <?php if (!empty($sesiones)): ?>
        <div class="sessions-list">
            <?php foreach ($sesiones as $sesion): ?>
            <div class="session-item">
                <div class="session-info">
                    <strong>IP: <?php echo htmlspecialchars($sesion['ip_address']); ?></strong>
                    <span>Dispositivo: <?php echo htmlspecialchars(substr($sesion['user_agent'], 0, 50)); ?>...</span>
                    <span>Última actividad: <?php echo date('d/m/Y H:i', strtotime($sesion['fecha_ultima_actividad'])); ?></span>
                </div>
                <?php if ($sesion['session_id'] !== session_id()): ?>
                <button class="btn btn-sm btn-danger" 
                        onclick="cerrarSesion('<?php echo $sesion['session_id']; ?>')">
                    Cerrar esta sesión
                </button>
                <?php else: ?>
                <span class="badge bg-success">Sesión actual</span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay sesiones activas.</p>
    <?php endif; ?>
</div>
<script>
function cerrarSesion(session_id) {
    if (confirm('¿Estás seguro de que quieres cerrar esta sesión?')) {
        fetch('172.20.10.3/CambaNet/public/?action=profile/cerrar-sesion', {
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
                alert('Error al cerrar la sesión');
            }
        });
    }
}
</script>