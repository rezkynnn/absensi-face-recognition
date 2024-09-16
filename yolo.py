import cv2
import time
import json
import numpy as np
from geopy.distance import geodesic
from deepface import DeepFace

# Load YOLO untuk deteksi wajah
net = cv2.dnn.readNet("yolov3-wider_16000.weights", "yolov3-face.cfg")
layer_names = net.getLayerNames()
output_layers = [layer_names[i[0] - 1] for i in net.getUnconnectedOutLayers()]

# Koordinat kantor (latitude, longitude)
office_location = (YOUR_OFFICE_LATITUDE, YOUR_OFFICE_LONGITUDE)
# Radius yang diperbolehkan (dalam meter)
allowed_radius = 100  # 100 meters

def is_within_radius(user_location):
    distance = geodesic(office_location, user_location).meters
    return distance <= allowed_radius

def validate_face(known_face_path, detected_face_img):
    try:
        result = DeepFace.verify(img1_path=known_face_path, img2_path=detected_face_img)
        return result["verified"]
    except Exception as e:
        print(f"Error in face validation: {e}")
        return False

def detect_and_validate_face(image_path, save_path, user_location, known_face_path):
    if not is_within_radius(user_location):
        return {"status": "error", "message": "Outside allowed radius"}
    
    img = cv2.imread(image_path)
    height, width, channels = img.shape

    # Prepare input
    blob = cv2.dnn.blobFromImage(img, 0.00392, (416, 416), (0, 0, 0), True, crop=False)
    net.setInput(blob)
    outs = net.forward(output_layers)

    # Process the outputs
    results = []
    for out in outs:
        for detection in out:
            scores = detection[5:]
            confidence = scores[0]  # YOLO untuk deteksi wajah
            if confidence > 0.5:
                center_x = int(detection[0] * width)
                center_y = int(detection[1] * height)
                w = int(detection[2] * width)
                h = int(detection[3] * height)
                x = int(center_x - w / 2)
                y = int(center_y - h / 2)

                # Simpan wajah yang terdeteksi
                face = img[y:y+h, x:x+w]
                timestamp = int(time.time())
                detected_face_path = f"{save_path}/face_{timestamp}.jpg"
                cv2.imwrite(detected_face_path, face)

                # Validasi wajah
                if validate_face(known_face_path, detected_face_path):
                    results.append({
                        "filename": detected_face_path,
                        "x": x,
                        "y": y,
                        "w": w,
                        "h": h,
                        "confidence": float(confidence)
                    })
    
    if results:
        return {"status": "success", "detections": results}
    else:
        return {"status": "error", "message": "Face validation failed"}

# Contoh penggunaan
user_location = (USER_LATITUDE, USER_LONGITUDE)  # Dapatkan dari frontend atau aplikasi
known_face_path = "known_faces/employee_1.jpg"  # Foto wajah karyawan yang sudah dikenali
result = detect_and_validate_face("image.jpg", "saved_faces", user_location, known_face_path)
print(json.dumps(result))
