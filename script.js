// Animación de entrada para cards
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.card, .form-card, .report-card').forEach((el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(16px)';
    el.style.transition = `opacity .4s ${i * 0.07}s, transform .4s ${i * 0.07}s`;
    requestAnimationFrame(() => {
      el.style.opacity = '1';
      el.style.transform = 'translateY(0)';
    });
  });

  // Confirmar eliminaciones
  document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', e => {
      if (!confirm('¿Estás seguro de eliminar este registro?')) e.preventDefault();
    });
  });
});