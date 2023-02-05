package com.example.myapplication

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.View
import android.widget.TextView
import com.android.volley.Request
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley

class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
    }

    fun getPredictionListener(view: View) {
        getPrediction()
    }

    fun getPrediction() {
        val textView = findViewById<TextView>(R.id.tv_prediction)

        val queue = Volley.newRequestQueue(this)
        val url = "http://192.168.33.5:8080/predict"

        val stringRequest = StringRequest(
            Request.Method.GET,
            url,
            { response -> textView.text = String.format(getString(R.string.response_success), response) },
            { error -> textView.text = error.toString() }
        )

        queue.add(stringRequest)
    }
}
