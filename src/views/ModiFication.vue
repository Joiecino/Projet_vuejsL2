<template>
  <div class="text-center bg-dark header" style="height: 100px">
    <h3 class="py-4">Gestion Appartement</h3>
  </div>
  <div class="row">
    <div class="col-2 bg-primary" style="height: 550px; top: 0">
      <div class="router_link" style="top: 140px; left: 10px">
        <button style="background-color: transparent">
          <router-link to="/" class="router btn2">Ajout</router-link></button
        ><br /><br />
        <button style="background-color: transparent">
          <router-link to="/liste" class="router btn3"
            >Listage</router-link
          ></button
        ><br /><br />
        <button style="background-color: transparent">
          <router-link to="/graphe" class="router btn2">Bilan</router-link>
        </button>
      </div>
      >
    </div>
    <div class="col position-relative my-3 bg-secondary" style="top: 84px">
      <div class="card d-flex flex-column my-5 mx-5 py-4 px-2 card-body">
        <div class="text-center">
          <h4>Modifier un appartement</h4>
        </div>
        <div class="">
          <form
            v-if="utilisateur"
            @submit.prevent="sauvegarderModifications"
            class="w-100"
          >
            <div class="py-2">
              <div class="text-start"><h6>Numéro d'appartement</h6></div>
              <div class="">
                <input
                  type="text"
                  name="numero"
                  v-model="utilisateur.numero"
                  id="numero"
                  class="form-control"
                />
              </div>
            </div>
            <div class="py-2">
              <div class="text-start"><h6>Désignation</h6></div>
              <div class="">
                <input
                  type="text"
                  name="designation"
                  v-model="utilisateur.designation"
                  id="design"
                  class="form-control"
                />
              </div>
            </div>
            <div class="py-2">
              <div class="text-start"><h6>Loyer (Ariary)</h6></div>
              <div class="">
                <input
                  type="text"
                  name="loyer"
                  v-model="utilisateur.loyer"
                  id="loyer"
                  class="form-control"
                />
              </div>
            </div>
            <div class="row">
              <button
                type="submit"
                class="col-1 mx-4 btn btn-secondary text-center"
                style="background-color: yellowgreen; width: 120px"
              >
                Sauvegarder
              </button>

              <button
                @click="$router.push('/liste')"
                class="col-1 text-light btn text-center"
                style="background-color: red; width: 120px"
              >
                Retour
              </button>
            </div>
          </form>
          <div v-if="error" style="color: red">{{ error }}</div>
          <p v-else-if="loading">
            Chargement des informations de l'utilisateur...
          </p>
        </div>
        <div
          v-if="message"
          style="
            color: green;
            width: 100%;
            background-color: black;
            height: 40px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 30px;
          "
          class="text-center my-2"
        >
          {{ message }}
        </div>
        <div
          v-if="error"
          style="
            color: red;
            width: 100%;
            background-color: black;
            height: 40px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 30px;
          "
          class="text-center my-2"
        >
          {{ error }}
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
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const utilisateurId = ref(route.params.id);
const utilisateur = ref(null);
const loading = ref(true);
const error = ref("");
const message = ref("");

const fetchUtilisateur = async () => {
  loading.value = true;
  error.value = "";
  try {
    const response = await fetch(
      `http://localhost/monAPI/selection_modifier.php?id=${utilisateurId.value}`
    );
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    if (data) {
      utilisateur.value = { ...data }; // Assurez-vous que votre backend renvoie un objet
    } else {
      error.value = "Utilisateur non trouvé.";
    }
  } catch (e) {
    error.value =
      "Erreur lors de la récupération des informations de l'utilisateur.";
    console.error("Erreur:", e);
  } finally {
    loading.value = false;
  }
};

const sauvegarderModifications = async () => {
  try {
    const response = await fetch("http://localhost/monAPI/modification.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(utilisateur.value),
    });

    const data = await response.json();
    if (response.ok && data.success) {
      message.value = "Modification réussie !";
    } else {
      error.value = "Modification échouée ! ";
    }
  } catch (e) {
    error.value =
      "Erreur de communication avec le serveur lors de la modification.";
    console.error("Erreur:", e);
  }
};

onMounted(() => {
  fetchUtilisateur();
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
  position: relative;
  justify-content: center;
  align-items: center;
  text-align: center;
  width: 100%;
  height: 100px;
  background-color: black;
  color: white;
  top: 15px;
}
</style>
