from flask import Flask, request, jsonify
from PIL import Image
import tensorflow as tf
import json
import numpy as np

# Define paths for model and encoded category/ingredient JSON files
MODEL_PATH = 'C:/Users/bilal/Desktop/senior models/food_v5/main/output_model.h5'
CATEGORY_JSON_PATH = 'C:/Users/bilal/Desktop/senior models/food_v5/main/encoded_food_categories.json'
INGREDIENTS_JSON_PATH = 'C:/Users/bilal/Desktop/senior models/food_v5/main/encoded_ingredients.json'

# Load the model
model = tf.keras.models.load_model(MODEL_PATH)

def preprocess_image(image):
    img = image.resize((224, 224))
    img_array = tf.keras.preprocessing.image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0)  # Expand dimensions to create batch of size 1
    img_array = tf.reshape(img_array, [-1, 224, 224, 3])
    return img_array

# Function to perform inference on an image
def predict_image(image, model):
    img = preprocess_image(image)
    outputs = model.predict(img)

    category_output = decode_category_prediction(outputs[0])
    ingredients_output = decode_ingredients_prediction(outputs[1])
    calorie_output = decode_single_value_prediction(outputs[2])
    carbs_output = decode_single_value_prediction(outputs[3])
    protein_output = decode_single_value_prediction(outputs[4])
    fat_output = decode_single_value_prediction(outputs[5])
    print (calorie_output)
    # Formatting the output
    formatted_output = {
        "category": category_output,
        "ingredients": ingredients_output,
        "calorie": str(calorie_output), 
        "carbs":str( carbs_output),      
        "protein": str (protein_output), 
        "fat": str (fat_output)
    }

    return formatted_output

# Function to decode category prediction
def decode_category_prediction(prediction):
    with open(CATEGORY_JSON_PATH, 'r') as f:
        encoded_categories = json.load(f)
    prediction = tf.reshape(prediction, [-1])
    index = tf.argmax(prediction)
    index = index.numpy()  # Convert TensorFlow tensor to NumPy array
    categories = list(encoded_categories.keys())  # List of category labels
    decoded_category = categories[index].replace('_', ' ')
    return decoded_category

# Function to decode ingredients prediction
def decode_ingredients_prediction(prediction):
    with open(INGREDIENTS_JSON_PATH, 'r') as f:
        encoded_ingredients = json.load(f)
    prediction = tf.reshape(prediction, [-1])
    values, indices = tf.math.top_k(prediction, k=10, sorted=True)
    indices = indices.numpy()  # Convert TensorFlow tensor to NumPy array
    values = values.numpy()    # Convert TensorFlow tensor to NumPy array
    ingredients = list(encoded_ingredients.keys())
    result = []
    for i in range(len(values)):
        if values[i] >= 0.5:  # Adjust threshold as needed
            result.append(ingredients[indices[i]])
        else:
            break
    return result

# Function to decode single value predictions (like calorie, carbs, protein, fat)
def decode_single_value_prediction(prediction):
    result_tensor = tf.reshape(prediction, [-1])
    value = result_tensor.numpy()
    return abs(value[0])

app = Flask(__name__)

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({'error': 'No image provided'}), 400
    
    image = request.files['image']
    img = Image.open(image.stream)
    prediction = predict_image(img, model)
    
    return jsonify(prediction)

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
