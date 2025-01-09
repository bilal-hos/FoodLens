class ImageModel {
  String? message;
  Data? data;

  ImageModel({this.message, this.data});

  ImageModel.fromJson(Map<String, dynamic> json) {
    message = json['message'];
    data = json['data'] != null ? Data.fromJson(json['data']) : null;
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['message'] = message;
    if (this.data != null) {
      data['data'] = this.data!.toJson();
    }
    return data;
  }
}

class Data {
  String? totalCalories;
  String? totalCarb;
  String? totalFat;
  String? totalProtine;
  int? totalMass;
  String? image;
  int? userId;
  int? id;

  Data(
      {this.totalCalories,
        this.totalCarb,
        this.totalFat,
        this.totalProtine,
        this.totalMass,
        this.image,
        this.userId,
        this.id});

  Data.fromJson(Map<String, dynamic> json) {
    totalCalories = json['total_calories'];
    totalCarb = json['total_carb'];
    totalFat = json['total_fat'];
    totalProtine = json['total_protine'];
    totalMass = json['total_mass'];
    image = json['image'];
    userId = json['user_id'];
    id = json['id'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['total_calories'] = totalCalories;
    data['total_carb'] = totalCarb;
    data['total_fat'] = totalFat;
    data['total_protine'] = totalProtine;
    data['total_mass'] = totalMass;
    data['image'] = image;
    data['user_id'] = userId;
    data['id'] = id;
    return data;
  }
}
