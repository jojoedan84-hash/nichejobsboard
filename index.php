<?php
// Pastikan bagian ini ada di paling ATAS file sebelum tag <html>
function getJobs($keyword = "Developer", $city = "Jakarta") {
    $curl = curl_init();
    // Menggabungkan keyword dan kota untuk pencarian yang akurat
    $query = urlencode($keyword . " in " . $city);

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://jsearch.p.rapidapi.com/search?query=$query&page=1&num_pages=1&country=id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: jsearch.p.rapidapi.com",
            "x-rapidapi-key: 19392c33b8msh26df55fb473e44ep1d36b3jsnb8f8d33fc27d" // Gunakan key Anda
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return null;
    } else {
        return json_decode($response, true);
    }
}

// Panggil datanya di sini
$jobsData = getJobs("Developer", "Jakarta");
?>

<!DOCTYPE html>
<html lang="id">
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-xl font-semibold mb-6">Lowongan Terbaru</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php 
            // Cek apakah data tersedia dan tipenya array
            if (isset($jobsData['data']) && is_array($jobsData['data'])): 
                foreach ($jobsData['data'] as $job): 
            ?>
                <div class="bg-white p-4 shadow rounded-lg">
                    <img src="<?= $job['employer_logo'] ?? 'https://via.placeholder.com/50' ?>" class="w-12 h-12 mb-2">
                    <h4 class="font-bold"><?= $job['job_title'] ?></h4>
                    <p class="text-sm text-gray-600"><?= $job['employer_name'] ?></p>
                    <p class="text-xs text-red-500 mt-2">📍 <?= $job['job_city'] ?? 'Indonesia' ?></p>
                    <a href="<?= $job['job_apply_link'] ?>" class="block mt-4 bg-red-600 text-white text-center py-2 rounded">Lamar</a>
                </div>
            <?php 
                endforeach; 
            else: 
            ?>
                <p>Maaf, lowongan tidak ditemukan atau API limit tercapai.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
