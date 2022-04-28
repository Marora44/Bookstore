package com.example.bookstore;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class OrderAdapter extends RecyclerView.Adapter<OrderAdapter.MyViewHolder> {

    JSONArray results;
    Context context;

    public OrderAdapter(Context c, JSONArray res){
        context = c;
        results = res;
    }

    @NonNull
    @Override
    public MyViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {

        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.cardview_search,parent,false);
        MyViewHolder viewHolder = new MyViewHolder(v);
        Log.d("createVH", "VH created");
        return viewHolder;
    }

    @Override
    public void onBindViewHolder(@NonNull MyViewHolder holder, int position) {
        try {
            JSONObject current = results.getJSONObject(position);
            holder.title.setText("ID: " + current.getString("id"));
            holder.author.setText("Total: $" + current.getString("total"));
            holder.price.setText("Placed on: " + current.getString("date"));
        } catch (JSONException e) {
            e.printStackTrace();
        }
        Log.d("createVH", "VH bind");
    }

    @Override
    public int getItemCount() {
        return results.length();
    }


    public class MyViewHolder extends RecyclerView.ViewHolder {

        TextView title, author, price;


        public MyViewHolder(View itemView) {
            super(itemView);
            title = itemView.findViewById(R.id.Title);
            author = itemView.findViewById(R.id.author);
            price = itemView.findViewById(R.id.price);

        }
    }
}


