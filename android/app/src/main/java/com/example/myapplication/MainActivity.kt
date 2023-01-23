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
        val textView = findViewById<TextView>(R.id.tv_prediction)

        val queue = Volley.newRequestQueue(this)
        val url = "http://http://localhost:8080/predict"

        val stringRequest = StringRequest(
            Request.Method.GET,
            url,
            { response -> textView.text = String.format(getString(R.string.response_success), response) },
            { textView.text = getString(R.string.response_fail) }
        )

        queue.add(stringRequest)
    }
}
