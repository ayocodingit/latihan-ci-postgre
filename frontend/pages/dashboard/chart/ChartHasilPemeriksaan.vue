<template>
  <div>
    <canvas :chart="chart" id="chartHasilPemeriksaan" />
  </div>
</template>

<script>
  var chartHasil;
  const dataChart = Object.freeze({
    datasets: [{
      label: 'Negatif',
      backgroundColor: '#2F80ED',
      data: []
    }, {
      label: 'Positif',
      backgroundColor: '#F2C94C',
      data: []
    }]
  });

  export default {
    props: ['barId'],
    name: 'chartHasil',
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
          type: "bar",
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
        let respNegatif = await this.$axios.get(`v2/chart/positif-negatif?tipe=${tipe}&jenis=negatif`);
        let respPositif = await this.$axios.get(`v2/chart/positif-negatif?tipe=${tipe}&jenis=positif`);

        this.chart.labels = respNegatif.data.label.length >= respPositif.data.label.length ?
          respNegatif.data.label : respPositif.data.label;
        this.chart.datasets = dataChart.datasets;
        this.chart.datasets[0].data = respNegatif.data.value || '';
        this.chart.datasets[1].data = respPositif.data.value || '';
        this.setChart(tipe);
      },
      setChart(tipe) {
        var ctx = document.getElementById("chartHasilPemeriksaan").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
          datasets: this.chart.datasets
        };

        chartHasil = new Chart(ctx, {
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
      this.$bus.$on('refresh-chart-hasil-pemeriksaan', (tipe) => {
        chartHasil.destroy();
        setTimeout(() => {
          this.loadData(tipe)
        }, 1000);
      })
    }
  };
</script>