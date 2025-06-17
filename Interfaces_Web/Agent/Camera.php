<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /Projet_Acces_lycee/Interfaces_Web/Index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion caméra et Barionet</title>
    <link rel="stylesheet" href="Camera.css">
    <script>
        function getBarionetIP() {
            return document.getElementById('ipAddress').value;
        }

        function controlOutput(output, value) {
            const ip = getBarionetIP();
            const url = "http://" + ip + "/rc.cgi?o=" + output + "," + value;
            fetch(url)
                .then(response => {
                    if (response.ok) {
                        showStatus(`Commande envoyée : Sortie ${output} - Valeur ${value}`, 'success');
                    } else {
                        showStatus('Erreur lors de l\'envoi de la commande', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        }

        function showStatus(message, type) {
            const statusDiv = document.getElementById('statusMessage');
            statusDiv.textContent = message;
            statusDiv.className = `status ${type}`;
        }
    </script>
</head>
<body>
    <h1>Interface de gestion caméra et ouverture de la porte</h1>

    <div class="main-container">
        <!-- Contrôles caméra -->
        <div class="controls">
            <h2>Contrôle de la caméra</h2>
            <table class="camera-table">
            <tr>
                    <td><form action="http://172.22.20.29/pantiltcontrol.cgi" method="post">
                        <input type="hidden" name="PanSingleMoveDegree" value="5">
                        <input type="hidden" name="TiltSingleMoveDegree" value="5">
                        <input type="hidden" name="PanTiltSingleMove" value="0">
                        <input type="submit" value="↖">
                    </form></td>
                    <td><form action="http://172.22.20.29/pantiltcontrol.cgi" method="post">
                        <input type="hidden" name="PanSingleMoveDegree" value="5">
                        <input type="hidden" name="TiltSingleMoveDegree" value="5">
                        <input type="hidden" name="PanTiltSingleMove" value="1">
                        <input type="submit" value="↑">
                    </form></td>
                    <td><form action="http://172.22.20.29/pantiltcontrol.cgi" method="post">
                        <input type="hidden" name="PanSingleMoveDegree" value="5">
                        <input type="hidden" name="TiltSingleMoveDegree" value="5">
                        <input type="hidden" name="PanTiltSingleMove" value="2">
                        <input type="submit" value="↗">
                    </form></td>
                </tr>
                <tr>
                    <td><form action="http://172.22.20.29/pantiltcontrol.cgi" method="post">
                        <input type="hidden" name="PanSingleMoveDegree" value="5">
                        <input type="hidden" name="TiltSingleMoveDegree" value="5">
                        <input type="hidden" name="PanTiltSingleMove" value="3">
                        <input type="submit" value="←">
                    </form></td>
                    <td><form action="http://172.22.20.29/pantiltcontrol.cgi" method="post">
                        <input type="hidden" name="PanSingleMoveDegree" value="5">
                        <input type="hidden" name="TiltSingleMoveDegree" value="5">
                        <input type="hidden" name="PanTiltSingleMove" value="4">
                        <input type="submit" class="reset-btn" value="⭯">
                    </form></td>
                    <td><form action="http://172.22.20.29/pantiltcontrol.cgi" method="post">
                        <input type="hidden" name="PanSingleMoveDegree" value="5">
                        <input type="hidden" name="TiltSingleMoveDegree" value="5">
                        <input type="hidden" name="PanTiltSingleMove" value="5">
                        <input type="submit" value="→">
                    </form></td>
                </tr>
                <tr>
                    <td><form action="http://172.22.20.29/pantiltcontrol.cgi" method="post">
                        <input type="hidden" name="PanSingleMoveDegree" value="5">
                        <input type="hidden" name="TiltSingleMoveDegree" value="5">
                        <input type="hidden" name="PanTiltSingleMove" value="6">
                        <input type="submit" value="↙">
                    </form></td>
                    <td><form action="http://172.22.20.29/pantiltcontrol.cgi" method="post">
                        <input type="hidden" name="PanSingleMoveDegree" value="5">
                        <input type="hidden" name="TiltSingleMoveDegree" value="5">
                        <input type="hidden" name="PanTiltSingleMove" value="7">
                        <input type="submit" value="↓">
                    </form></td>
                    <td><form action="http://172.22.20.29/pantiltcontrol.cgi" method="post">
                        <input type="hidden" name="PanSingleMoveDegree" value="5">
                        <input type="hidden" name="TiltSingleMoveDegree" value="5">
                        <input type="hidden" name="PanTiltSingleMove" value="8">
                        <input type="submit" value="↘">
                    </form></td>
                </tr>
            </table>
        </div>

        <!-- Flux vidéo -->
        <div class="video-feed">
            <img src="http://172.22.20.29/video/mjpg.cgi" alt="Flux vidéo caméra D-Link">
        </div>
    </div>

    <!-- Contrôles Barionet -->
    <div class="barionet-container">
        <h2>Gestion d'accès</h2>

        <div class="config-section">
            <div class="input-group hidden">
                <label for="ipAddress">Adresse IP:</label>
                <input type="text" id="ipAddress" value="172.22.21.164">
            </div>
        </div>

        <div class="controls">
            <div class="output-group">
                <h3>Ouverture de la porte</h3>
                <button onclick="controlOutput(1, 10)" class="btn">OUVRIR</button>
            </div>

            <div class="output-group">
                <h3>Modes de fonctionnement</h3>
                <button onclick="controlOutput(2, 0)" class="btn">Badge ou Agent</button>
                <button onclick="controlOutput(2, 1)" class="btn">Mode Auto</button>
            </div>
        </div>

        <div id="statusMessage" class="status"></div>
        </div>
    </div>
    <div class="return-button">
            <a href="espace_agent.php">Retour à la page d'accueil</a>
    </div>   
</body>
</html>
