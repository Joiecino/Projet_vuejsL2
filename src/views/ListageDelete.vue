<template>
  // En-tête page
  <div class="text-center bg-dark header d-flex" style="height: 100px">
    <h3 class="py-4">Gestion Appartement</h3>
  </div>

  // Trois menus de la page ajout, listage, graphe
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
    </div>
    <div class="col bg-secondary" style="width: 100%; height: 550px">
      <div
        class="text-center bg-light my-5 mx-5"
        style="
          top: 80px;
          position: relative;
          border-radius: 20px;
          height: 400px;
        "
      >
        <h6 class="text-dark pt-2">Liste des appartements</h6>
        <div
          style="
            overflow-y: auto;
            overflow-x: hidden;
            width: 100%;
            height: 320px;
          "
        >
          <table
            v-if="utilisateurs.length > 0"
            border="1"
            class="table table-dark table-striped table-bordered"
          >
            <thead>
              <tr>
                <th>Numéro</th>
                <th>Designation</th>
                <th class="px-4">Loyer (Ariary)</th>
                <th>Observation</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-group-divider">
              <tr v-for="utilisateur in utilisateurs" :key="utilisateur.id">
                <td class="px-4" style="display: none">
                  {{ utilisateur.id }}
                </td>
                <td class="px-4">{{ utilisateur.numero }}</td>
                <td class="px-5">{{ utilisateur.designation }}</td>
                <td>{{ utilisateur.loyer }}</td>
                <td>
                  <span class="px-4">
                    {{ getStatusText(utilisateur.loyer) }}
                  </span>
                </td>
                <td class="px-4">
                  <button
                    @click="modifierUtilisateur(utilisateur.id)"
                    class="px-4"
                  >
                    Modifier
                  </button>
                  <button
                    @click="supprimerUtilisateur(utilisateur.id)"
                    class="bg-warning"
                  >
                    Supprimer
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <!--div v-if="error" style="color: red">{{ error }}</div-->
          <p v-else-if="!loading">Aucun utilisateur trouvé.</p>
          <p v-if="loading">Chargement des utilisateurs...</p>
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
        >
          {{ error }}
        </div>
      </div>
    </div>
    <div class="row position-relative">
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
import { useRouter } from "vue-router";

const utilisateurs = ref([]);
const loading = ref(true);
const error = ref("");
const router = useRouter();
const message = ref("");

const fetchUtilisateurs = async () => {
  loading.value = true;
  error.value = "";
  try {
    const response = await fetch("http://localhost/monAPI/selection.php");
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    utilisateurs.value = data;
  } catch (e) {
    error.value = "Erreur lors de la récupération des utilisateurs.";
    console.error("Erreur:", e);
  } finally {
    loading.value = false;
  }
};

// Obtenir le texte de statut en fonction du loyer
const getStatusText = (loyer) => {
  if (loyer < 1000) return "Bas";
  if (loyer >= 1000 && loyer <= 5000) return "Moyen";
  return "Élevé";
};

const modifierUtilisateur = (id) => {
  router.push(`/modifier/${id}`);
};

const supprimerUtilisateur = async (id) => {
  if (
    confirm(
      `Êtes-vous sûr de vouloir supprimer l'utilisateur avec l'ID ${id} ?`
    )
  ) {
    try {
      const response = await fetch("http://localhost/monAPI/supprimer.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id }),
      });

      const data = await response.json();
      if (response.ok && data.success) {
        // Recharger la liste des utilisateurs après la suppression
        fetchUtilisateurs();
        message.value = "Suppression réussie !";
      } else {
        error.value = "Suppression échouée !";
      }
    } catch (e) {
      error.value =
        "Erreur de communication avec le serveur lors de la suppression.";
      console.error("Erreur:", e);
    }
  }
};

onMounted(() => {
  fetchUtilisateurs();
});
</script>

<style>
.body {
  background-color: black;
  width: 100%;
  height: 560px;
  top: 0px;
  overflow: hidden;
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
  width: 100px;
  background-color: blue;
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
  height: 100px;
  background-color: black;
  color: white;
  top: 0px;
}
.contenu {
  display: flex;
  position: relative;
  top: 16px;
  justify-content: center;
  align-items: center;
  text-align: center;
}

table {
  width: 100%;
  border-collapse: collapse;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

th,
td {
  padding: 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  background-color: var(--dark);
  color: white;
}

tr:nth-child(even) {
  background-color: #f9f9f9;
}

tr:hover {
  background-color: #f1f1f1;
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
  background-color: rgb(13, 24, 15);
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
