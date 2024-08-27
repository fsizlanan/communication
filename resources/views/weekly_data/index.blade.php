<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kümülatif Toplamlar</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Kümülatif Toplamlar</h1>
    <canvas id="cumulativeChart" width="800" height="400"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('cumulativeChart').getContext('2d');
            var data = @json($data);

            console.log(data);

            if (!Array.isArray(data)) {
                console.error('Data is not an array:', data);
                return;
            }

            var labels = [];
            var datasets = [];

            data.forEach((item, index) => {
                labels.push(`Individual ${index + 1}`);
                datasets.push({
                    label: `Individual ${index + 1}`,
                    data: item,
                    borderColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 1)`,
                    fill: false
                });
            });

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array.from({ length: 52 }, (_, i) => `Week ${i + 1}`),
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Week'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Cumulative Sum'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
