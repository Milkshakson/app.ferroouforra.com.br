import React from "react";
import { Bar } from "react-chartjs-2";
import { dolarFormat } from "../../utils/moneyUtils";
import Chart from 'chart.js/auto';
function BarChart(props) {
  const { chartData, title, displayLegends } = props
  return <Bar options={{
    scales: {
      y: {
        ticks: {
          // Include a dollar sign in the ticks
          callback: function (value, index, ticks) {
            return dolarFormat(value);
          }
        }
      }
    },
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
        display: displayLegends === true
      },
      title: {
        display: true,
        text: title || '',
      },
    },
  }} data={chartData} />;
}

export default BarChart;
