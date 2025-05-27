import requests
import time
import sys

API_KEY = "372b792b02a93906c7b827acc015904a542ffac75b597c1ceb741d7a3ded51fe"
VT_URL = "https://www.virustotal.com/api/v3/files"

def subir_archivo(ruta_archivo):
    """Sube un archivo a VirusTotal y devuelve el ID del análisis."""
    headers = {"x-apikey": API_KEY}
    files = {"file": open(ruta_archivo, "rb")}
    response = requests.post(VT_URL, headers=headers, files=files)
    if response.status_code == 200:
        return response.json()["data"]["id"]
    else:
        print("Error al subir archivo:", response.json())
        return None

def obtener_resultado(analisis_id):
    """Consulta el resultado del análisis en VirusTotal."""
    url = f"https://www.virustotal.com/api/v3/analyses/{analisis_id}"
    headers = {"x-apikey": API_KEY}

    while True:
        response = requests.get(url, headers=headers)
        if response.status_code == 200:
            data = response.json()
            status = data["data"]["attributes"]["status"]
            if status == "completed":
                return data["data"]["attributes"]["stats"]
            else:
                print("Análisis en progreso...")
                time.sleep(10)
        else:
            print("Error al obtener resultado:", response.json())
            return None

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Uso: python analizar.py <ruta_del_archivo>")
        sys.exit(1)

    ruta_archivo = sys.argv[1]
    analisis_id = subir_archivo(ruta_archivo)
    if analisis_id:
        resultado = obtener_resultado(analisis_id)
        print("Resultado del análisis:", resultado)
