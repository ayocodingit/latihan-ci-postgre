<template>
  <canvas :chart="chart" id="totalSampelMasuk" />
</template>

<script>
  var ChartSampel;
  const dataChart = Object.freeze({
    datasets: [{
      label: 'Sampel Masuk',
      backgroundColor: '#27AE60',
      borderColor: '',
      data: []
    }]
  });
  export default {
    props: ['barId'],
    name: 'ChartSampel',
    data() {
      return {
        chart: {
          labels: [
            "Mon",
            "Tue",
            "Wed",
            "Thu",
            "Fri",
            "Sat",
            "Sun"
          ],
          datasets: [{
            label: 'Sampel Masuk',
            backgroundColor: '#27AE60',
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
        }
      }
    },
    methods: {
      async loadData(tipe) {
        try {
          let resp = await this.$axios.get(`v2/chart/sampel?tipe=${tipe}`)
          this.chart.datasets = dataChart.datasets;
          this.chart.datasets[0].data = resp.data.value
          this.chart.labels = resp.data.label
        } catch (e) {
          this.chart.datasets = dataChart.datasets;
        }
        this.setChart(tipe);
      },
      setChart(tipe) {
        var ctx = document.getElementById("totalSampelMasuk").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
          datasets: this.chart.datasets
        };

        ChartSampel = new Chart(ctx, {
          type: this.chart.type,
          data: chartData,
          options: this.chart.options,
        })
      },
    },
    created() {
      setTimeout(() => {
        this.loadData('Daily');
      }, 1000);
    },
    mounted() {
      this.$bus.$on('refresh-chart-admin-sampel', (tipe) => {
        setTimeout(() => {
          ChartSampel.destroy();
          this.loadData(tipe)
        }, 1000);
      })
    }
  };
</script>