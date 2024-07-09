import sys
import cv2

def check_image_for_face(image_path):
    try:
        # Load the pre-trained face detection model
        face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
        
        # Read the image
        image = cv2.imread(image_path)
        
        if image is None:
            sys.exit("Error: Unable to read the image.")
        
        # Convert the image to grayscale (for better face detection)
        gray_image = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
        
        # Detect faces in the image
        faces = face_cascade.detectMultiScale(gray_image, scaleFactor=1.3, minNeighbors=5, minSize=(30, 30))
        
        # Check if no face or more than one face is detected
        if len(faces) == 0:
            sys.exit("Error: No face detected in the image.")
        elif len(faces) > 1:
            sys.exit("Error: More than one face detected in the image.")
        
        # Check lighting conditions (mean brightness)
        brightness = cv2.mean(gray_image)[0]
        if brightness < 50 or brightness > 200:
            sys.exit("Warning: Image lighting may affect face recognition performance.")
        
        # Check if the face is centrally positioned
        face = faces[0]
        image_height, image_width = gray_image.shape
        face_center_x = face[0] + face[2] / 2
        face_center_y = face[1] + face[3] / 2
        if (face_center_x < image_width * 0.3 or face_center_x > image_width * 0.7 or
            face_center_y < image_height * 0.3 or face_center_y > image_height * 0.7):
            sys.exit("Warning: Face is not centrally positioned in the image.")
        
        return True

    except Exception as e:
        sys.exit("Error: {}".format(e))

# Test the function with an image
if __name__ == "__main__":
    image_path = sys.argv[1]  # Image path passed as argument
    try:
        result = check_image_for_face(image_path)
        if result:
            print("true")
        else:
            print("false")
    except Exception as e:
        sys.exit("false: {}".format(e))
