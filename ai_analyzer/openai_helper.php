<?php
function analyzeWithOpenAI($prompt){
    $url = "https://openrouter.ai/api/v1/chat/completions";
    $apiKey = "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"; // вставь свой ключ

    $data = [
        "model"=>"gpt-4o-mini",
        "messages"=>[
            ["role"=>"system","content"=>"Ты эксперт HR и консультант по образованию. Анализируй резюме и GPA, давай советы чётко, структурированно и подсказывай университеты."],
            ["role"=>"user","content"=>$prompt]
        ],
        "max_tokens"=>1000,
        "temperature"=>0.7
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 60
    ]);

    $response = curl_exec($ch);
    if(curl_errno($ch)){
        $err = curl_error($ch);
        curl_close($ch);
        return "Ошибка CURL: $err";
    }
    curl_close($ch);

    $result = json_decode($response,true);
    return $result['choices'][0]['message']['content'] ?? "Нет ответа от API";
}
?>
