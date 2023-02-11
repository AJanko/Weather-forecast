package com.example.myapplication

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.View
import android.widget.EditText
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

        val lat = findViewById<EditText>(R.id.lat).text
        val lon = findViewById<EditText>(R.id.lon).text
        val url = String.format(getString(R.string.predict_url), lat, lon)

        val queue = Volley.newRequestQueue(this)

        val stringRequest = StringRequest(
            Request.Method.GET,
            url,
            { response -> textView.text = response },
            { error -> textView.text = error.toString() }
        )

        queue.add(stringRequest)
    }
}
