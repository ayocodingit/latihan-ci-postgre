<template>
  <div>
    <canvas :chart="chart" id="chartEkstraksiPcr" />
  </div>
</template>

<script>
  var chartEkstraksiPcr;
  const dataChart = Object.freeze({
    datasets: [{
      label: 'Sampel Ekstraksi',
      fill: false,
      borderColor: '#2F80ED',
      data: []
    }, {
      label: 'Sampel PCR',
      fill: false,
      borderColor: '#F2C94C',
      data: []
    }]
  });

  export default {
    props: ['lineId'],
    name: 'ChartEkstraksiPcr',
    data() {
      return {
        chart: {
          labels: [],
          datasets: [{
            label: '',
            backgroundColor: '',
            borderColor: '',
            data: []
          }, {
            label: '',
            backgroundColor: '',
            borderColor: '',
            data: []
          }],
          type: "line",
          options: {
            responsive: true,
            scales: {
              xAxes: [{
                stacked: true,
                gridLines: {
                  display: false,
                },
              }],
              yAxes: [{
                stacked: true,
              }]
            }
          },
          options1: {
            responsive: true,
            scales: {
              xAxes: [{
                stacked: true,
                gridLines: {
                  display: false,
                },
                ticks: {
                  autoSkip: false,
                  maxRotation: 90,
                  minRotation: 90
                }
              }],
              yAxes: [{
                stacked: true,
              }]
            }
          }
        }
      }
    },
    methods: {
      async loadData(tipe) {
        let respEkstraksi = await this.$axios.get(`v2/chart/ekstraksi?tipe=${tipe}`);
        let respPcr = await this.$axios.get(`v2/chart/pcr?tipe=${tipe}`);

        this.chart.labels = respEkstraksi.data.label >= respPcr.data.label ?
          respEkstraksi.data.label : respPcr.data.label;
        this.chart.datasets = dataChart.datasets;
        this.chart.datasets[0].data = respEkstraksi.data.value || '';
        this.chart.datasets[1].data = respPcr.data.value || '';
        this.setChart(tipe);
      },
      setChart(tipe) {
        var ctx = document.getElementById("chartEkstraksiPcr").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
          datasets: this.chart.datasets
        };

        chartEkstraksiPcr = new Chart(ctx, {
          type: this.chart.type,
          data: chartData,
          options: tipe === 'Daily' ? this.chart.options : this.chart.options1
        });
      }
    },
    created() {
      setTimeout(() => {
        this.loadData('Daily');
      }, 1000);
    },
    mounted() {
      this.$bus.$on('refresh-chart-ekstraksi-pcr', (tipe) => {
        chartEkstraksiPcr.destroy();
        setTimeout(() => {
          this.loadData(tipe)
        }, 1000);
      })
    }
  };
</script>