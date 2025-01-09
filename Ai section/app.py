from flask import Flask, request, jsonify
import tensorflow as tf
import numpy as np
import json

app = Flask(__name__)

# Load the model
model = tf.keras.models.load_model('C:/Users/bilal/Desktop/senior models/food_v5/main/output_model.h5')

# Preprocess image function
def preprocess_image(image_path):
    img = tf.keras.preprocessing.image.load_img(image_path, target_size=(224, 224))
    img_array = tf.keras.preprocessing.image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0)  # Expand dimensions to create batch of size 1
    img_array = tf.reshape(img_array, [-1, 224, 224, 3])
    return img_array

# Prediction functions
def decode_category_prediction(prediction):
    with open('C:/Users/bilal/Desktop/senior models/food_v5/main/encoded_food_categories.json', 'r') as f:
        encoded_categories = json.load(f)
    prediction = tf.reshape(prediction, [-1])
    index = tf.argmax(prediction).numpy()
    categories = list(encoded_categories.keys())
    decoded_category = categories[index].replace('_', ' ')
    return decoded_category

def decode_ingredients_prediction(prediction):
    with open('C:/Users/bilal/Desktop/senior models/food_v5/main/encoded_ingredients.json', 'r') as f:
        encoded_ingredients = json.load(f)
    prediction = tf.reshape(prediction, [-1])
    values, indices = tf.math.top_k(prediction, k=10, sorted=True)
    indices = indices.numpy()
    values = values.numpy()
    ingredients = list(encoded_ingredients.keys())
    result = [ingredients[indices[i]] for i in range(len(values)) if values[i] >= 0.5]
    return result

def decode_single_value_prediction(prediction):
    result_tensor = tf.reshape(prediction, [-1])
    value = result_tensor.numpy()
    return abs(value[0])

def predict_image(image_path, model):
    img = preprocess_image(image_path)
    outputs = model.predict(img)
    return {
        "category": decode_category_prediction(outputs[0]),
        "ingredients": decode_ingredients_prediction(outputs[1]),
        "calorie": decode_single_value_prediction(outputs[2]),
        "carbs": decode_single_value_prediction(outputs[3]),
        "protein": decode_single_value_prediction(outputs[4]),
        "fat": decode_single_value_prediction(outputs[5])
    }

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files  not in request.form:
        return jsonify({'error': 'No image or mass provided'}), 400
    
    image = request.files['image']
    
    image_path = f'/tmp/{image.filename}'
    image.save(image_path)
    
    predictions = predict_image(image_path, model)
    
    return jsonify(predictions)

if __name__ == '__main__':
    app.run(debug=True)
