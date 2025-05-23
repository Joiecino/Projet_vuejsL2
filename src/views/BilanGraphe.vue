<template>
  <div class="bg-light">
    <canvas ref="histogramCanvas"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import {
  Chart,
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";

// Enregistrez les composants nécessaires de Chart.js
Chart.register(BarController, BarElement, CategoryScale, LinearScale);

const histogramCanvas = ref(null);
const val = ref(null);
let totale;
let mine;
let maxe;

onMounted(async () => {
  try {
    const response = await fetch("http://localhost/monAPI/graphe.php");
    //const data = await response.json();

    //const apiData = response.data;
    const apiData = await response.json();
    if (Array.isArray(apiData) && apiData.length > 0) {
      val.value = apiData[0];
      totale = val.value.totale;
      mine = val.value.mine;
      maxe = val.value.maxe;
    }

    renderChart();
  } catch (err) {
    console.error("Erreur lors de la récupération des données :", err);
  }
});

const renderChart = async () => {
  const ctx = histogramCanvas.value.getContext("2d");

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: ["Total", "Minimum", "Maximum"],
      datasets: [
        {
          label: "Nombre", // Personnalisez l'étiquette
          data: [totale, mine, maxe],
          backgroundColor: [
            "rgba(255, 99, 132, 0.2)",
            "rgba(54, 162, 235, 0.2)",
            "rgba(255, 206, 86, 0.2)",
          ],
          //"#42A5F5", "#66BB6A", "#FFA726"
          borderColor: [
            "rgba(255,99,132,1)",
            "rgba(54, 162, 235, 1)",
            "rgba(255, 206, 86, 1)",
          ],
          borderWidth: 1,
          pointBorderColor: "#2554FF",
        },
      ],
    },
    options: {
      scales: {
        yAxes: [
          {
            ticks: {
              beginAtZero: true,
            },
            gridLines: {
              display: true,
            },
          },
        ],
        xAxes: [
          {
            gridLines: {
              display: false,
            },
          },
        ],
      },
      responsive: true,
      maintainAspectRatio: true,
    },
  });
};
</script>

<style scoped>
canvas {
  width: 100%;
  height: 300px; /* Ajustez la hauteur selon vos besoins */
}
</style>
