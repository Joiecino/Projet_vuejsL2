<template>
  <div class="bg-light body">
    <div class="text-center bg-dark header d-flex" style="height: 100px">
      <h3 class="py-4">Gestion Appartement</h3>
    </div>
    <div class="row">
      <div class="col-2 bg-primary" style="height: 570px; top: 0">
        <div class="router_link" style="top: 140px; left: 10px">
          <button style="background-color: transparent">
            <router-link to="/" class="router btn3">Ajout</router-link></button
          ><br /><br />
          <button style="background-color: transparent">
            <router-link to="/liste" class="router btn2"
              >Listage</router-link
            ></button
          ><br /><br />
          <button style="background-color: transparent">
            <router-link to="/graphe" class="router btn2">Bilan</router-link>
          </button>
        </div>
      </div>
      <div class="col position-relative me-0 bg-secondary" style="top: 100px">
        <div class="card d-flex flex-column my-5 mx-5 py-4 px-2 card-body">
          <div class="text-center">
            <h4>Ajouter un appartement</h4>
          </div>
          <div class="">
            <form @submit.prevent="enregistrerUtilisateur" class="w-100">
              <div class="py-2">
                <div class="text-start"><h6>Numéro d'appartement</h6></div>
                <div class="">
                  <input
                    type="text"
                    name="numero"
                    v-model="numero"
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
                    v-model="designation"
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
                    v-model="loyer"
                    id="loyer"
                    class="form-control"
                  />
                </div>
              </div>
              <div class="">
                <input type="submit" value="Ajouter" class="btn btn-primary" />
              </div>
            </form>
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
              class="text-center"
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
              class="text-center"
            >
              {{ error }}
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
import { ref } from "vue";

const numero = ref("");
const designation = ref("");
const loyer = ref("");
const message = ref("");
const error = ref("");

const enregistrerUtilisateur = async () => {
  message.value = "";
  error.value = "";

  try {
    const response = await fetch("http://localhost/monAPI/teste.php", {
      // Ajustez le chemin si nécessaire
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        numero: numero.value,
        designation: designation.value,
        loyer: loyer.value,
      }),
    });

    const data = await response.json();

    if (response.ok) {
      const tmpo = data.message;
      console.log(tmpo);
      message.value = "Insertion réussie !";
      numero.value = "";
      designation.value = "";
      loyer.value = "";
    } else {
      error.value = "Insertion échouée !";
    }
  } catch (err) {
    error.value = "Erreur de connexion au serveur.";
    console.error("Erreur:", err);
  }
};
</script>

<style>
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
button {
  border: none;
  background-color: transparent;
}
.router_link {
  position: relative;
  top: 80px;
  padding: 10px;
  border: none;
  background-color: transparent;
}
.router {
  text-decoration: none;
  color: black;
  border: none;
  background-color: transparent;
}
.footer {
  position: relative;
  width: 100%;
  height: 110px;
  background-color: black;
  color: white;
  top: 555px;
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
  background-color: rgb(11, 14, 11);
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
