<?php

echo "Script started...<br>";

// 1. Database Connection
$conn = require __DIR__ . '/db_connect.php';

if (!$conn) {
    die("Connection failed. Check db_connect.php.");
}
echo "Database connected successfully.<br>";

// 2. Your Data
$roomData = [
  // [
  //   "name" => "Standard Double Room",
  //   "deal" => "Limited-time Deal",
  //   "description" => "Beds: 1 double or 2 singles. Breakfast included · Free cancellation.",
  //   "price" => "₹3,000",
  //   "maxAdults" => 2,
  //   "maxChildren" => 1,
  //   "images" => [
  //     "big" => "https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
  //     "small1" => "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
  //     "small2" => "https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
  //     "small3" => "https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
  //   ],
  // ],
  // [
  //   "name" => "Deluxe Sea View Room",
  //   "deal" => "Premium Choice",
  //   "description" => "Larger room with a king-size bed. Balcony with sea view · Breakfast & dinner included.",
  //   "price" => "₹8,000",
  //   "maxAdults" => 2,
  //   "maxChildren" => 2,
  //   "images" => [
  //     "big" => "https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3",
  //     "small1" => "https://images.unsplash.com/photo-1564501049412-61c2a3083791?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3",
  //     "small2" => "https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
  //     "small3" => "https://images.unsplash.com/photo-1505691723518-36a5ac3be353?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
  //   ],
  // ],
  // [
  //   "name" => "Executive Suite",
  //   "deal" => "Luxury Pick",
  //   "description" => "Luxury suite with a living area and Jacuzzi. King-size bed · Complimentary drinks.",
  //   "price" => "₹12,000",
  //   "maxAdults" => 3,
  //   "maxChildren" => 2,
  //   "images" => [
  //     "big" => "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
  //     "small1" => "https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
  //     "small2" => "https://images.unsplash.com/photo-1590490359854-dfba196da72c?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
  //     "small3" => "https://images.unsplash.com/photo-1559599101-f09722fb4948?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
  //   ],
  // ],
  [
    "name" => "Classic Single Room",
    "deal" => "Solo Traveler",
    "description" => "A cozy room perfect for one. 1 single bed · Free airport shuttle.",
    "price" => "₹2,500",
    "maxAdults" => 1,
    "maxChildren" => 0,
    "images" => [
      "big" => "https://images.unsplash.com/photo-1560185893-a55d8c180e04?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
      "small1" => "https://images.unsplash.com/photo-1551882547-ff40c63fe5f4?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small2" => "https://images.unsplash.com/photo-1594910600109-9a036c63b398?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small3" => "https://images.unsplash.com/photo-1629140534220-72e5068f56e9?q=80&w=2069&auto=format&fit=crop&ixlib-rb-4.0.3",
    ],
  ],
  [
    "name" => "Penthouse Suite",
    "deal" => "Ultimate Luxury",
    "description" => "Top-floor suite with panoramic city views and a private terrace. 2 king beds · Butler service.",
    "price" => "₹45,000",
    "maxAdults" => 4,
    "maxChildren" => 2,
    "images" => [
      "big" => "https://images.unsplash.com/photo-1616046229579-c909c2d31f08?q=80&w=1974&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small1" => "https://images.unsplash.com/photo-1616486029423-aaa47893110e?q=80&w=1932&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small2" => "https://images.unsplash.com/photo-1600121848594-d8644e57b80a?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small3" => "https://images.unsplash.com/photo-1595526114035-0d45ab12c541?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
    ],
  ],
  [
    "name" => "Twin Room - Mountain View",
    "deal" => "Great for Friends",
    "description" => "Features two comfortable single beds and a stunning mountain view. 2 single beds.",
    "price" => "₹4,200",
    "maxAdults" => 2,
    "maxChildren" => 0,
    "images" => [
      "big" => "https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
      "small1" => "https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small2" => "https://images.unsplash.com/photo-1590490359854-dfba196da72c?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small3" => "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
    ],
  ],
  [
    "name" => "Accessible Room",
    "deal" => "Comfort & Access",
    "description" => "Wheelchair accessible room with roll-in shower and support rails. 1 queen bed.",
    "price" => "₹3,100",
    "maxAdults" => 2,
    "maxChildren" => 1,
    "images" => [
      "big" => "https://images.unsplash.com/photo-1560448204-603b3fc33ddc?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small1" => "https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?q=80&w=2071&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small2" => "https://images.unsplash.com/photo-1605346452771-f9f10f279169?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small3" => "https://images.unsplash.com/photo-1532323544230-7191fd5e9e4d?q=80&w=1974&auto=format&fit=crop&ixlib-rb-4.0.3",
    ],
  ],
  [
    "name" => "Oceanfront Suite with Balcony",
    "deal" => "Direct Sea Access",
    "description" => "Wake up to the sound of waves. Direct beach access. 1 king bed · Private balcony.",
    "price" => "₹14,500",
    "maxAdults" => 2,
    "maxChildren" => 2,
    "images" => [
      "big" => "https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
      "small1" => "https://images.unsplash.com/photo-1505691723518-36a5ac3be353?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small2" => "https://images.unsplash.com/photo-1564501049412-61c2a3083791?q=80&w=1974&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small3" => "https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
    ],
  ],
  [
    "name" => "Studio Apartment",
    "deal" => "Extended Stay",
    "description" => "Features a kitchenette and living area. 1 queen bed · Weekly cleaning.",
    "price" => "₹6,800",
    "maxAdults" => 2,
    "maxChildren" => 1,
    "images" => [
      "big" => "https://images.unsplash.com/photo-1560185009-dddeb820c7b7?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small1" => "https://images.unsplash.com/photo-1512915922686-57c11f41920B?q=80&w=2071&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small2" => "https://images.unsplash.com/photo-1602002418349-6a3f169046f2?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small3" => "https://images.unsplash.com/photo-1502672023488-70e25813380e?q=80&w=1974&auto=format&fit=crop&ixlib-rb-4.0.3",
    ],
  ],
  [
    "name" => "Connecting Rooms",
    "deal" => "Group Friendly",
    "description" => "Two adjoining rooms with a lockable door between them. 1 king bed, 2 single beds.",
    "price" => "₹9,000",
    "maxAdults" => 4,
    "maxChildren" => 2,
    "images" => [
      "big" => "https://images.unsplash.com/photo-1598928636135-d146006ff4be?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small1" => "httpsimages.unsplash.com/photo-1596394516093-501ba68a0ba6?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small2" => "https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small3" => "https://images.unsplash.com/photo-1594882645126-14020914d58d?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
    ],
  ],
  [
    "name" => "Budget Double Room",
    "deal" => "Smart Choice",
    "description" => "A simple, clean room with essentials. 1 double bed · No breakfast.",
    "price" => "₹2,200",
    "maxAdults" => 2,
    "maxChildren" => 0,
    "images" => [
      "big" => "https://images.unsplash.com/photo-1591088398332-8a7791972843?q=80&w=1974&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small1" => "https://images.unsplash.com/photo-1592229505726-a9c36211e4f4?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small2" => "httpsmimages.unsplash.com/photo-1576675784201-0e142b423952?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small3" => "https://images.unsplash.com/photo-1576495199011-eb94736d05d6?q=80&w=2071&auto=format&fit=crop&ixlib-rb-4.0.3",
    ],
  ],
  [
    "name" => "Presidential Suite",
    "deal" => "Exclusive Luxury",
    "description" => "Our finest suite with a dining room, study, and Jacuzzi. 1 king bed · All-inclusive.",
    "price" => "₹60,000",
    "maxAdults" => 2,
    "maxChildren" => 2,
    "images" => [
      "big" => "https://images.unsplash.com/photo-1608198399988-5c6c34a25e17?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small1" => "https://images.unsplash.com/photo-1568495112521-3c6b2b80a22c?q=80&w=1974&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small2" => "https://images.unsplash.com/photo-1596701062351-8c2c14d0f6d9?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small3" => "https://images.unsplash.com/photo-1600210491892-71c8466e0ab5?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
    ],
  ],
  [
    "name" => "Cabin in the Woods",
    "deal" => "Nature Retreat",
    "description" => "A rustic and private cabin away from the main building. 1 queen bed · Fireplace.",
    "price" => "₹11,000",
    "maxAdults" => 2,
    "maxChildren" => 0,
    "images" => [
      "big" => "https://images.unsplash.com/photo-1506974210756-8e1b8985d348?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small1" => "https://images.unsplash.com/photo-1494512165618-3a5116e0364f?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small2" => "https://images.unsplash.com/photo-1525944372996-23b10ab4074c?q=80&w=1974&auto=format&fit=crop&ixlib-rb-4.0.3",
      "small3" => "httpsmimages.unsplash.com/photo-1508873699735-643f9c6c0dab?q=80&w=2070&auto=format&fit=crop&ixlib-rb-4.0.3",
    ],
  ],
];

// 3. Prepare SQL Statement
$sql = "INSERT INTO hotels (name, deal, description, price, maxAdults, maxChildren, image_big, image_small1, image_small2, image_small3) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    die('Query prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
}

// 4. Loop Through Data and Insert
echo "Starting data insertion...<br>";

foreach ($roomData as $room) {
    
    // Clean the price: remove '₹' and ','
    $price_clean = str_replace(['₹', ','], '', $room['price']);
    // Convert to an integer
    $price_int = (int)$price_clean;

    // Bind parameters
    $types = "sssiiissss";
    mysqli_stmt_bind_param(
        $stmt,
        $types,
        $room['name'],
        $room['deal'],
        $room['description'],
        $price_int,
        $room['maxAdults'],
        $room['maxChildren'],
        $room['images']['big'],
        $room['images']['small1'],
        $room['images']['small2'],
        $room['images']['small3']
    );

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Successfully inserted: " . htmlspecialchars($room['name']) . "<br>";
    } else {
        echo "Error inserting " . htmlspecialchars($room['name'])." : " . htmlspecialchars(mysqli_stmt_error($stmt)) . "<br>";
    }
}

echo "Data insertion complete.<br>";

// 5. Close Connections
mysqli_stmt_close($stmt);
mysqli_close($conn);

?>