    // --- Configuration Commune ---
    Chart.defaults.font.family = "'Segoe UI', 'Helvetica', 'Arial', sans-serif";
    Chart.defaults.color = '#666';

    // --- 1. GRAPHIQUE DONUT (Répartition) ---
    const ctxDoughnut = document.getElementById('demands-chart').getContext('2d');
    
    // Données
    const dataDoughnut = {
        labels: ['En attente', 'Messe Célébrés', 'Messe Confirmé'],
        datasets: [{
            data: [8.3, 65.8, 25.9],
            backgroundColor: [
                '#F3C56F', // Jaune/Orange
                '#95E1AA', // Vert Menthe
                '#6283D0'  // Bleu
            ],
            borderWidth: 0,
            hoverOffset: 10 // Animation au survol
        }]
    };

    const doughnutChart = new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: dataDoughnut,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%', // Taille du trou au milieu
            plugins: {
                legend: {
                    display: false // On cache la légende par défaut pour faire la nôtre en HTML
                },
                tooltip: {
                    enabled: true
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Génération de la légende HTML personnalisée (pour avoir le look exact Text .... %)
    const legendContainer = document.getElementById('custom-legend');
    dataDoughnut.labels.forEach((label, index) => {
        const percent = dataDoughnut.datasets[0].data[index] + '%';
        const color = dataDoughnut.datasets[0].backgroundColor[index];
        
        const item = document.createElement('div');
        item.className = 'legend-item';
        item.innerHTML = `
            <div class="legend-info">
                <span class="legend-color" style="background-color: ${color}"></span>
                <span>${label}</span>
            </div>
            <span class="legend-percent">${percent}</span>
        `;
        legendContainer.appendChild(item);
    });


    // --- 2. GRAPHIQUE LINEAIRE (Évolution) ---
    const ctxLine = document.getElementById('offrandes-chart').getContext('2d');

    const lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil'],
            datasets: [
                {
                    label: 'Cette année',
                    data: [120, 80, 140, 245, 210, 230, 240],
                    borderColor: '#444444', // Gris foncé/Noir
                    backgroundColor: 'transparent',
                    borderWidth: 1.5,
                    pointRadius: 0, // Pas de points sur la ligne
                    pointHoverRadius: 6,
                    tension: 0.4, // Courbe lissée (Bezier)
                },
                {
                    label: "l'année dernière",
                    data: [50, 120, 205, 70, 130, 250, 300],
                    borderColor: '#99B9E8', // Bleu clair
                    backgroundColor: 'transparent',
                    borderWidth: 1.5,
                    borderDash: [5, 5], // Ligne pointillée
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'start', // Aligner à gauche
                    labels: {
                        usePointStyle: true, // Utiliser des cercles au lieu de rectangles
                        boxWidth: 8,
                        padding: 20,
                        color: '#333'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 350,
                    grid: {
                        display: false, // Pas de grille verticale apparente sur l'image
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 100,
                        color: '#aaa'
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#aaa'
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart' // Animation fluide du tracé
            }
        }
    });