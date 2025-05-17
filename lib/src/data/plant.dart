class Plant {
  final String id;
  final String name;
  final String nickname;
  final String image;

  Plant({
    required this.id,
    required this.name,
    required this.nickname,
    required this.image,
  });

  factory Plant.fromJson(Map<String, dynamic> json) {
    return Plant(
      id: json['id'],
      name: json['name'],
      nickname: json['nickname'],
      image: json['image'],
    );
  }
}
