<template>
  <div>
    <canvas :chart="chart" id="chartRegist" />
  </div>
</template>

<script>
  var chartRegistrasi;
  const dataChart = Object.freeze({
    datasets: [{
      label: 'Registrasi Mandiri',
      backgroundColor: '#2F80ED',
      data: []
    }, {
      label: 'Registrasi Rujukan',
      backgroundColor: '#F2C94C',
      data: []
    }]
  });

  export default {
    props: ['barId'],
    name: 'chartRegistrasi',
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
        let respMandiri = await this.$axios.get(`v2/chart/regis?tipe=${tipe}&jenis=mandiri`);
        let respRujukan = await this.$axios.get(`v2/chart/regis?tipe=${tipe}&jenis=rujukan`);

        this.chart.labels = respMandiri.data.label.length >= respRujukan.data.label ?
          respMandiri.data.label : respRujukan.data.label;
        this.chart.datasets = dataChart.datasets;
        this.chart.datasets[0].data = respMandiri.data.value || '';
        this.chart.datasets[1].data = respRujukan.data.value || '';
        this.setChart(tipe);
      },
      setChart(tipe) {
        var ctx = document.getElementById("chartRegist").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
          datasets: this.chart.datasets
        };

        chartRegistrasi = new Chart(ctx, {
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
      this.$bus.$on('refresh-chart-registrasi', (tipe) => {
        chartRegistrasi.destroy();
        setTimeout(() => {
          this.loadData(tipe)
        }, 1000);
      })
    }
  };
</script>