#!/usr/bin/python3

import sys
import os.path
import face_recognition

def batch_compare_faces(got_image_path, existing_image_path):
    # Load the single image to compare
    got_image = face_recognition.load_image_file(got_image_path)
    got_image_facialfeatures = face_recognition.face_encodings(got_image, num_jitters=10)
    
    if not got_image_facialfeatures:
        print("Error: No face found in the provided image.")
        sys.exit(1)
    
    got_image_facialfeatures = got_image_facialfeatures[0]

    # Load and encode all existing images in the folder
    existing_image_encodings = {}
    existing_image_file = os.path.basename(existing_image_path)  # Get the file name of the existing image
    existing_image = face_recognition.load_image_file(existing_image_path)
    existing_image_facialfeatures = face_recognition.face_encodings(existing_image, num_jitters=10)

    if existing_image_facialfeatures:
        existing_image_encodings[existing_image_file] = existing_image_facialfeatures[0]

    # Compare the single image to the existing image
    for existing_image_file, existing_image_encoding in existing_image_encodings.items():
        distance = face_recognition.face_distance([existing_image_encoding], got_image_facialfeatures)

        if distance[0] < 0.4:
            filename_without_extension = os.path.splitext(existing_image_file)[0]
            print("true")
            sys.exit(1)

    print("false")
    return False

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print("Usage: python face_recognition.py <got_image_path> <existing_images_folder>")
        sys.exit(1)

    got_image_path = sys.argv[1]
    existing_image_path = sys.argv[2]

    batch_compare_faces(got_image_path, existing_image_path)
