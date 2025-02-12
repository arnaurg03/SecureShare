import sys
import requests

def analyze_file(api_key, file_path):
    url = 'https://www.virustotal.com/vtapi/v2/file/scan'
    params = {'api_key': api_key}
    files = {'file': (file_path, open(file_path, 'rb'))}
    response = requests.post(url, files=files, params=params)
    return response.json()

if __name__ == '__main__':
    if len(sys.argv) != 3:
        print("Uso: python3 analyze_file.py <API_KEY> <FILE_PATH>")
        sys.exit(1)

    api_key = sys.argv[1]
    file_path = sys.argv[2]

    result = analyze_file(api_key, file_path)
    print(result)
