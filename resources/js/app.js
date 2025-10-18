// Dynamically import Chart.js and render charts when canvases are present

async function initializeCharts() {
  const attendanceCanvas = document.getElementById('attendanceChart');
  const clubCanvas = document.getElementById('clubPerformanceChart');

  if (!attendanceCanvas && !clubCanvas) {
    return;
  }

  const { Chart } = await import('chart.js/auto');

  if (attendanceCanvas) {
    const labels = safeParseJson(attendanceCanvas.dataset.labels) ?? [];
    const present = safeParseJson(attendanceCanvas.dataset.present) ?? [];
    const total = safeParseJson(attendanceCanvas.dataset.total) ?? [];

    new Chart(attendanceCanvas, {
      type: 'line',
      data: {
        labels,
        datasets: [
          {
            label: 'Present',
            data: present,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
          },
          {
            label: 'Total',
            data: total,
            borderColor: 'rgb(156, 163, 175)',
            backgroundColor: 'rgba(156, 163, 175, 0.1)',
            tension: 0.4,
            fill: false,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(148, 163, 184, 0.1)' },
          },
          x: {
            grid: { display: false },
          },
        },
      },
    });
  }

  if (clubCanvas) {
    const labels = safeParseJson(clubCanvas.dataset.labels) ?? [];
    const values = safeParseJson(clubCanvas.dataset.values) ?? [];

    new Chart(clubCanvas, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          {
            label: 'Attendance Rate (%)',
            data: values,
            backgroundColor: [
              'rgba(16, 185, 129, 0.8)',
              'rgba(59, 130, 246, 0.8)',
              'rgba(139, 92, 246, 0.8)',
              'rgba(245, 158, 11, 0.8)',
              'rgba(239, 68, 68, 0.8)',
            ],
            borderColor: [
              'rgb(16, 185, 129)',
              'rgb(59, 130, 246)',
              'rgb(139, 92, 246)',
              'rgb(245, 158, 11)',
              'rgb(239, 68, 68)',
            ],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            grid: { color: 'rgba(148, 163, 184, 0.1)' },
          },
          x: {
            grid: { display: false },
          },
        },
      },
    });
  }
}

function safeParseJson(maybeJson) {
  try {
    return JSON.parse(maybeJson ?? 'null');
  } catch {
    return null;
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeCharts);
} else {
  initializeCharts();
}

