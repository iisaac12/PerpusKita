// PerpusKita Interactions

document.addEventListener('DOMContentLoaded', () => {
    // Page load animation
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.6s ease-out';
    setTimeout(() => {
        document.body.style.opacity = '1';
    }, 100);

    // Sidebar interaction for mobile
    const sidebar = document.querySelector('.sidebar');
    if (window.innerWidth <= 768) {
        // Simple touch feedback
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('touchstart', () => {
                item.style.transform = 'scale(0.95)';
            });
            item.addEventListener('touchend', () => {
                item.style.transform = 'scale(1)';
            });
        });
    }

    // Glass Card Parallax Effect (Subtle)
    const cards = document.querySelectorAll('.glass-card');
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0)';
        });
    });

    // Form Handling Mock
    const borrowForm = document.getElementById('borrowForm');
    if (borrowForm) {
        borrowForm.addEventListener('submit', (e) => {
            console.log('Form submitted physically (handled by HTML action)');
        });
    }
});

// Dynamic background glow following mouse
const glow = document.createElement('div');
glow.style.cssText = `
    position: fixed;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(167, 139, 250, 0.1) 0%, transparent 70%);
    pointer-events: none;
    z-index: -1;
    transform: translate(-50%, -50%);
    transition: width 0.3s, height 0.3s;
`;
document.body.appendChild(glow);

document.addEventListener('mousemove', (e) => {
    glow.style.left = e.clientX + 'px';
    glow.style.top = e.clientY + 'px';
});
