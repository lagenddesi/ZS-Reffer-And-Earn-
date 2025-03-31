// General utility functions
document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-calculate ROI on investment forms
    document.querySelectorAll('.roi-calculator').forEach(function(element) {
        element.addEventListener('input', function() {
            const amount = parseFloat(this.value) || 0;
            const roiPercent = parseFloat(this.dataset.roi) || 0;
            const duration = parseInt(this.dataset.duration) || 1;
            
            const roiAmount = (amount * roiPercent / 100 * duration).toFixed(2);
            const totalReturn = (amount + parseFloat(roiAmount)).toFixed(2);
            
            document.getElementById(this.dataset.roiTarget).textContent = roiAmount;
            document.getElementById(this.dataset.returnTarget).textContent = totalReturn;
        });
    });
});

// Copy to clipboard function
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Show feedback
    const originalText = element.nextElementSibling.innerHTML;
    element.nextElementSibling.innerHTML = 'Copied!';
    setTimeout(function() {
        element.nextElementSibling.innerHTML = originalText;
    }, 2000);
}
