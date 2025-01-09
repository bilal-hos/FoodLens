import 'package:FeedLens/layout/cubit/fitness_cubit.dart';
import 'package:FeedLens/shared/components/constants.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:image_picker/image_picker.dart';
import 'dart:io';
import 'package:FeedLens/shared/styles/colors.dart';
import 'package:lottie/lottie.dart';
import 'package:percent_indicator/circular_percent_indicator.dart';

class DailyMealsScreen extends StatefulWidget {
  @override
  _DailyMealsScreenState createState() => _DailyMealsScreenState();
}

class _DailyMealsScreenState extends State<DailyMealsScreen> {
  File? _image;
  bool _isLoading = false;
  List<String>? _ingredients;
  String? _category;
  int? _calories;
  int? _fat;
  int? _mass;
  int? _protein;
  int? _carb;
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _massController = TextEditingController();

  Future<void> _pickImage(ImageSource source) async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: source);

    if (pickedFile != null) {
      setState(() {
        _image = File(pickedFile.path);
      });
      await _scanImageToServer(_image!);
    }
  }

  Future<void> _scanImageToServer(File image) async {
    var cubit = FitnessCubit.get(context);
    setState(() {
      _isLoading = true;
    });
    await cubit.scanImage(image).then((value) {
      setState(() {
        _isLoading = false;
        if (cubit.scanImageModel != null) {
          _ingredients = cubit.scanImageModel?.ingredients;
          _category = cubit.scanImageModel!.category;
          _calories = cubit.scanImageModel!.totalCalories;
          _fat = cubit.scanImageModel!.totalFat;
          _protein = cubit.scanImageModel!.totalProtine;
          _carb = cubit.scanImageModel!.totalCarb;
        }
      });
    });
  }

  void _removeImage() {
    var cubit = FitnessCubit.get(context);
    setState(() {
      _image = null;
      _calories = null;
      _fat = null;
      _mass = null;
      _protein = null;
      _carb = null;
      _massController.clear();
      cubit.scanImageModel = null;
    });
  }

  @override
  Widget build(BuildContext context) {
    var cubit = FitnessCubit.get(context);
    return BlocConsumer<FitnessCubit, FitnessState>(
      listener: (context, state) {
        // TODO: implement listener
      },
      builder: (context, state) {
        return Scaffold(
          appBar: AppBar(
            title: const Text('Add Meals'),
            backgroundColor: defaultColor,
          ),
          body: SingleChildScrollView(
            physics: const BouncingScrollPhysics(),
            child: cubit.loadingGoals
                ? const Center(
                    child: CircularProgressIndicator(color: defaultColor),
                  )
                : Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Form(
                      key: _formKey,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          if (_image != null)
                            Card(
                              elevation: 5,
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(15),
                              ),
                              child: Column(
                                children: [
                                  ClipRRect(
                                    borderRadius: BorderRadius.circular(15),
                                    child: Image.file(
                                      _image!,
                                      height: 300,
                                      fit: BoxFit.cover,
                                    ),
                                  ),
                                  const SizedBox(height: 10),
                                  if (_isLoading || cubit.loadingAddImage)
                                    const Padding(
                                      padding: EdgeInsets.all(8.0),
                                      child: CircularProgressIndicator(),
                                    )
                                  else if (_calories != null && _fat != null)
                                    Padding(
                                      padding: const EdgeInsets.all(16.0),
                                      child: Column(
                                        children: [
                                          Padding(
                                            padding: const EdgeInsets.symmetric(
                                                horizontal: 15),
                                            child: Row(
                                              mainAxisAlignment:
                                                  MainAxisAlignment.center,
                                              children: [
                                                Text(_category ?? "",
                                                    style: const TextStyle(
                                                        fontWeight:
                                                            FontWeight.bold,
                                                        fontSize: 28)),
                                              ],
                                            ),
                                          ),
                                          Row(
                                            mainAxisAlignment:
                                            MainAxisAlignment.center,
                                            children: const [
                                              Text("per serving / 100g",
                                                  style: TextStyle(
                                                    color: Colors.teal,
                                                      fontWeight:
                                                      FontWeight.bold,
                                                      fontSize: 20)),
                                            ],
                                          ),
                                          Padding(
                                            padding: const EdgeInsets.symmetric(
                                                horizontal: 15),
                                            child: Row(
                                              children: [
                                                Row(
                                                  children: [
                                                    const Icon(
                                                        Icons
                                                            .local_fire_department,
                                                        color: Colors.orange),
                                                    const SizedBox(width: 5),
                                                    const Text('Calories: ',
                                                        style: TextStyle(
                                                            fontWeight:
                                                                FontWeight.bold,
                                                            fontSize: 18)),
                                                    Text(
                                                      '$_calories kcal',
                                                      style: const TextStyle(
                                                          fontSize: 18),
                                                    ),
                                                  ],
                                                ),
                                                const Spacer(),
                                                Row(
                                                  children: [
                                                    const Icon(Icons.fastfood,
                                                        color: Colors.red),
                                                    const SizedBox(width: 5),
                                                    const Text('Fat: ',
                                                        style: TextStyle(
                                                            fontWeight:
                                                                FontWeight.bold,
                                                            fontSize: 18)),
                                                    Text(
                                                      '$_fat g',
                                                      style: const TextStyle(
                                                          fontSize: 18),
                                                    ),
                                                  ],
                                                ),
                                              ],
                                            ),
                                          ),
                                          Padding(
                                            padding: const EdgeInsets.symmetric(
                                                horizontal: 25),
                                            child: Row(
                                              children: [
                                                Row(
                                                  children: [
                                                    const Icon(Icons.cake,
                                                        color: Colors.brown),
                                                    const SizedBox(width: 5),
                                                    const Text('Carb: ',
                                                        style: TextStyle(
                                                            fontWeight:
                                                                FontWeight.bold,
                                                            fontSize: 18)),
                                                    Text(
                                                      '$_carb g',
                                                      style: const TextStyle(
                                                          fontSize: 18),
                                                    ),
                                                  ],
                                                ),
                                                const Spacer(),
                                                Row(
                                                  mainAxisAlignment:
                                                      MainAxisAlignment.center,
                                                  children: [
                                                    const Icon(Icons.fitness_center,
                                                        color: Colors.green),
                                                    const SizedBox(width: 5),
                                                    const Text('Protein: ',
                                                        style: TextStyle(
                                                            fontWeight:
                                                                FontWeight.bold,
                                                            fontSize: 18)),
                                                    Text(
                                                      '$_protein g',
                                                      style: const TextStyle(
                                                          fontSize: 18),
                                                    ),
                                                  ],
                                                ),
                                              ],
                                            ),
                                          ),
                                          Padding(
                                            padding: const EdgeInsets.symmetric(
                                                horizontal: 25),
                                            child: Row(
                                              children: const [
                                                Icon(Icons.fastfood,
                                                    color: Colors.blue),
                                                SizedBox(width: 5),
                                                Text('Ingredients:',
                                                    style: TextStyle(
                                                        fontWeight:
                                                            FontWeight.bold,
                                                        fontSize: 18)),
                                              ],
                                            ),
                                          ),
                                          ListView.builder(
                                            shrinkWrap: true,
                                            padding: const EdgeInsets.symmetric(
                                                horizontal: 60),
                                            physics:
                                                const NeverScrollableScrollPhysics(),
                                            itemBuilder: (context, index) =>
                                                Text(
                                                    "* ${_ingredients![index]}",
                                                    style: const TextStyle(
                                                        fontWeight:
                                                            FontWeight.bold,
                                                        fontSize: 18)),
                                            itemCount: _ingredients!.length,
                                          ),
                                          Padding(
                                            padding: const EdgeInsets.all(8.0),
                                            child: TextFormField(
                                              controller: _massController,
                                              keyboardType:
                                                  TextInputType.number,
                                              decoration: InputDecoration(
                                                labelText: 'Mass (g)',
                                                border: OutlineInputBorder(
                                                  borderRadius:
                                                      BorderRadius.circular(10),
                                                ),
                                              ),
                                              validator: (value) {
                                                if (value == null ||
                                                    value.isEmpty) {
                                                  return 'Please enter the mass of the meal';
                                                }
                                                return null;
                                              },
                                              onSaved: (value) {
                                                _mass = int.tryParse(value!);
                                              },
                                            ),
                                          ),
                                          Row(
                                            mainAxisAlignment:
                                                MainAxisAlignment.spaceEvenly,
                                            children: [
                                              ElevatedButton.icon(
                                                onPressed: _removeImage,
                                                icon: const Icon(Icons.delete,
                                                    color: Colors.white),
                                                label: const Text('Remove'),
                                                style: ElevatedButton.styleFrom(
                                                  backgroundColor: Colors.red,
                                                ),
                                              ),
                                              ElevatedButton.icon(
                                                onPressed: () {
                                                  if (_formKey.currentState!
                                                      .validate()) {
                                                    _formKey.currentState!
                                                        .save();
                                                    cubit.addImage(
                                                      image: _image!,
                                                      mass: _massController.text,
                                                      totalCalories: _calories!,
                                                      totalCarb: _carb!,
                                                      totalFat: _fat!,
                                                      totalProtein: _protein!,
                                                      category: _category!
                                                    );
                                                    _removeImage();
                                                  }
                                                },
                                                icon: const Icon(Icons.add,
                                                    color: Colors.white),
                                                label: const Text('Add'),
                                                style: ElevatedButton.styleFrom(
                                                  backgroundColor: defaultColor,
                                                ),
                                              ),
                                            ],
                                          ),
                                        ],
                                      ),
                                    )
                                ],
                              ),
                            )
                          else
                            Lottie.asset(
                              'assets/lottie/placeholder.json',
                              height: 300,
                              frameRate:
                                  FrameRate(30), // Make Lottie animation slower
                            ),
                          const SizedBox(height: 20),
                          ElevatedButton.icon(
                            onPressed: () => _pickImage(ImageSource.camera),
                            icon: const Icon(Icons.camera),
                            label: const Text('Take a Photo'),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: defaultColor,
                              padding: const EdgeInsets.symmetric(vertical: 15),
                              textStyle: const TextStyle(fontSize: 16),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(10),
                              ),
                            ),
                          ),
                          const SizedBox(height: 20),
                          ElevatedButton.icon(
                            onPressed: () => _pickImage(ImageSource.gallery),
                            icon: const Icon(Icons.photo_library),
                            label: const Text('Choose from Gallery'),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: defaultColor,
                              padding: const EdgeInsets.symmetric(vertical: 15),
                              textStyle: const TextStyle(fontSize: 16),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(10),
                              ),
                            ),
                          ),
                          const SizedBox(height: 20),
                          CircularPercentIndicator(
                            addAutomaticKeepAlive: true,
                            radius: 120.0,
                            lineWidth: 14.0,
                            animation: true,
                            percent: cubit.userData.user!.neededCalories == null
                                ? 1.0
                                : getPercentage(),
                            animateFromLastPercent: true,
                            animationDuration: 2000,
                            backgroundColor: Colors.grey,
                            backgroundWidth: 15,
                            header: const Text('Your Progress!',
                                style: TextStyle(
                                    fontWeight: FontWeight.bold,
                                    fontSize: 18,
                                    color: defaultColor)),
                            center: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              crossAxisAlignment: CrossAxisAlignment.center,
                              children: [
                                Text(
                                  "${cubit.goalsModel!.data!.remainingCalories} Cal",
                                  style: const TextStyle(
                                      fontWeight: FontWeight.bold,
                                      fontSize: 18.0),
                                ),
                                const Text(
                                  "Remaining",
                                  style: TextStyle(
                                      fontWeight: FontWeight.w600,
                                      fontSize: 14.0),
                                ),
                              ],
                            ),
                            footer: Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              crossAxisAlignment: CrossAxisAlignment.center,
                              children: [
                                const Icon(Icons.local_fire_department,
                                    color: Colors.orange),
                                const SizedBox(
                                  width: 5,
                                ),
                                Text(
                                  "Base Goal: ${cubit.userData.user!.neededCalories} Cal",
                                  style: const TextStyle(
                                      fontWeight: FontWeight.bold,
                                      fontSize: 17.0),
                                ),
                              ],
                            ),
                            circularStrokeCap: CircularStrokeCap.round,
                            progressColor: cubit.redProgressColor
                                ? Colors.red[900]
                                : defaultColor,
                          ),
                        ],
                      ),
                    ),
                  ),
          ),
        );
      },
    );
  }

  double getPercentage() {
    var cubit = FitnessCubit.get(context);
    double result = 1 -
        ((cubit.goalsModel!.data!.remainingCalories!) /
                cubit.userData.user!.neededCalories!.toDouble())
            .toDouble();

    if (result == 1 || result > 1) {
      result = 1;
    }
    return result;
  }
}
