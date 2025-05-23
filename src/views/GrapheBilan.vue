<template>
  <div class="bg-light body">
    <div class="text-center bg-dark header d-flex" style="height: 100px">
      <h3 class="py-4">Gestion Appartement</h3>
    </div>
    <div class="row">
      <div class="col-2 bg-primary" style="height: 560px; top: 100">
        <div class="router_link" style="top: 140px; left: 10px">
          <button style="background-color: transparent">
            <router-link to="/" class="router btn2">Ajout</router-link></button
          ><br /><br />
          <button style="background-color: transparent">
            <router-link to="/liste" class="router btn2"
              >Listage</router-link
            ></button
          ><br /><br />
          <button style="background-color: transparent">
            <router-link to="/graphe" class="router btn3">Bilan</router-link>
          </button>
        </div>
      </div>
      <div class="col-6 position-relative mx-3 my-4">
        <div
          class="card d-flex flex-column mx-2 py-4 px-2 card-body"
          style="top: 100px"
        >
          <div class="text-center">
            <h4>Visualisation du bilan en graphe</h4>
            <BilanGraphe />
          </div>
        </div>
      </div>
      <div class="col position-relative me-1" style="top: 100px; left: 10px">
        <div style="width: 100%; height: 550px; top: 0" class="bg-secondary">
          <div class="py-5">
            <p class="py-5" v-if="loadingBilan">Chargement du bilan</p>
            <p class="py-5" v-if="errorBilan">
              Erreur du chargement du bilan: {{ errorBilan }}
            </p>
            <div class="py-2 mx-5">
              <div v-if="BilanTitre">
                <div
                  class="card d-flex flex-column my-4 mx-2 py-2 px-2 card-body bg-light"
                >
                  <h4>Loyer Total:</h4>
                  <h4 class="text-center" style="color: rgba(255, 99, 132, 1)">
                    {{ BilanTitre[0].totale }}
                  </h4>
                </div>
                <div
                  class="card d-flex flex-column my-4 mx-2 py-2 px-2 card-body bg-light"
                >
                  <h4>Loyer Maximum:</h4>
                  <h4 class="text-center" style="color: rgba(255, 206, 86, 1)">
                    {{ BilanTitre[0].maxe }}
                  </h4>
                </div>
                <div
                  class="card d-flex flex-column my-2 mx-2 py-2 px-2 card-body bg-light"
                >
                  <h4>Loyer Minimum:</h4>
                  <h4 class="text-center" style="color: rgba(54, 162, 235, 1)">
                    {{ BilanTitre[0].mine }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="footer">
          <div class="contenu">
            <img
              src="../assets/etoiles.png"
              alt="Etoile"
              style="width: 30px; height: 30px"
              class="me-2"
            />
            <img
              src="../assets/etoiles.png"
              alt="Etoile"
              style="width: 30px; height: 30px"
              class="me-2"
            />
            <img
              src="../assets/etoiles.png"
              alt="Etoile"
              style="width: 30px; height: 30px"
              class="me-2"
            />
            <img
              src="../assets/etoiles.png"
              alt="Etoile"
              style="width: 30px; height: 30px"
              class="me-2"
            />
            <img
              src="../assets/etoiles.png"
              alt="Etoile"
              style="width: 30px; height: 30px"
              class="me-2"
            />
            <h3>Hotel</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import BilanGraphe from "./BilanGraphe.vue";

const BilanTitre = ref("");
const loadingBilan = ref(false);
const errorBilan = ref(null);

async function fetchUtilisateurs() {
  loadingBilan.value = true;
  errorBilan.value = null;
  BilanTitre.value = "";

  try {
    const response = await fetch("http://localhost/monAPI/graphe_bilan.php");
    if (!response.ok) {
      let errorMessage =
        "Erreur HTTP: ${response.status} ${response.statusText}";
      try {
        const errorData = await response.json();
        if (errorData && errorData.message) {
          errorMessage = errorData.message;
        }
      } catch (e) {
        //comment
      }
      throw new Error(errorMessage);
    }
    const data = await response.json();

    if (data) {
      BilanTitre.value = data;
    } else {
      console.warn(
        "La reponse de l'APi ne contient pas la cle 'titreDynamique' ",
        data
      );
      BilanTitre.value = "Données du bilan non disponible";
    }
  } catch (err) {
    console.warn("impossible de recuperer les données ", err);
    BilanTitre.value = err.message || "une erreurvinconnue est survenue.";
  } finally {
    loadingBilan.value = false;
    console.log(BilanTitre.value);
  }
}
onMounted(() => {
  fetchUtilisateurs();
});
</script>
<style scoped>
.body {
  background-color: black;
  width: 100%;
  height: 560px;
  top: 0px;
}
.header {
  position: absolute;
  width: 100%;
  height: 60px;
  justify-content: center;
  align-items: center;
  text-align: center;
  top: 0;
  color: white;
}

.router_link {
  position: relative;
  top: 80px;
  padding: 10px;
}
.router {
  text-decoration: none;
  color: black;
}
.footer {
  position: absolute;
  justify-content: center;
  width: 100%;
  height: 110px;
  background-color: black;
  color: white;
  top: 560px;
  margin-left: 13px;
}
.contenu {
  display: flex;
  position: relative;
  top: 16px;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.btn2 {
  width: 100px;
  height: 50px;
  background-color: #ffc107;
  animation: pulse 2s infinite;
  display: inline-block;
  margin: 10px;
  text-align: center;
  line-height: 50px;
  color: black;
}

.btn3 {
  width: 100px;
  height: 50px;
  background-color: rgb(4, 8, 5);
  animation: pulse 2s infinite;
  display: inline-block;
  margin: 10px;
  text-align: center;
  line-height: 50px;
  color: rgba(231, 231, 238, 0.9);
}
@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}
</style>
